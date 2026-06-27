# Postman Testing Guide for Bookshop API

## Overview
This guide explains how to test all API routes in Postman, including authentication requirements.

## Base URL
Assuming your local development server is running at:
```
http://localhost:8000
```
If using Herd, it might be:
```
http://bookshop.test
```

## Authentication Notes
- **API routes in `routes/api.php`** use Sanctum token authentication for protected routes
- **Web routes in `routes/admin.php` and `routes/web.php`** use session authentication with CSRF protection
- For API testing via Postman, use Sanctum tokens from `/api/staff/login` endpoint
- Do **not** use the web login (`/admin/login`) for API testing - it requires CSRF tokens and uses session auth

## Staff Login (Get Sanctum Token)
Required for protected staff/admin API routes.

**POST** `http://localhost:8000/api/staff/login`

**Body (x-www-form-urlencoded):**
```
email: admin@nathanielgyarteng.com
password: fx$!f@patience
```

**Success Response:**
```json
{
  "success": true,
  "token": "your-sanctum-token-here",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@nathanielgyarteng.com",
    "role": "admin",
    "is_staff": true
  }
}
```
**Save the token** for use in Authorization headers.

## API Endpoints to Test

### 1. Create Staff User (Open - No Auth)
**POST** `http://localhost:8000/api/admin/staff`

**Body:**
```
name: Test Staff
email: staff@example.com
phone_number: 1234567890
password: secret123
password_confirmation: secret123
role: employee
```

### 2. Get All Staff Users (Open)
**GET** `http://localhost:8000/api/admin/users`

### 3. Get Pending Attendances (Open)
**GET** `http://localhost:8000/api/admin/attendance/pending`

### 4. Approve Attendance (Open)
**PUT** `http://localhost:8000/api/admin/attendance/{id}/approve`
*(Replace {id} with an attendance ID from pending list)*

### 5. Reject Attendance (Open)
**PUT** `http://localhost:8000/api/admin/attendance/{id}/reject`

### 6. Staff Attendance Summary (Protected - Requires Staff Token)
**GET** `http://localhost:8000/api/admin/attendance/summary`

**Headers:**
```
Authorization: Bearer your-sanctum-token
Accept: application/json
```

### 7. User Attendance Summary (Protected - Requires Staff Token)
**GET** `http://localhost:8000/api/admin/attendance/{userId}/summary`
*(Replace {userId} with a staff user ID)*

**Headers:**
```
Authorization: Bearer your-sanctum-token
Accept: application/json
```

### 8. Staff Login (Get Token for Staff Routes)
**POST** `http://localhost:8000/api/staff/login`
*(Same as above - use staff credentials)*

### 9. My Attendance (Protected - Requires Staff Token)
**GET** `http://localhost:8000/api/attendance/mine`

**Headers:**
```
Authorization: Bearer your-sanctum-token
Accept: application/json
```

## Important Notes

1. **Admin Summary Routes Require Admin Privileges**: 
   - The `/admin/attendance/summary` and `/admin/attendance/{userId}/summary` endpoints check for `is_admin` in addition to authentication
   - Your staff login credentials must belong to a user with both `is_staff=true` and `is_admin=true` in the database
   - The provided admin credentials (`admin@nathanielgyarteng.com`) should work if that user is marked as staff

2. **CSRF Token Errors**:
   - If you attempt to use `/admin/login` (web route) for API testing, you'll get CSRF errors
   - For API routes, **only** use the `/api/staff/login` endpoint to get tokens
   - Web routes require session cookies and CSRF tokens - not suitable for Postman API testing

3. **Database Setup**:
   - Ensure you have at least one staff user in the database (created via `/api/admin/staff` or existing)
   - For testing admin summary routes, ensure that staff user also has `is_admin=1`
   - You may need to create some attendance records for testing pending/approval flows

4. **Server Status**:
   - Make sure your Laravel development server is running: `php artisan serve`
   - Verify the server is accessible at your chosen base URL

## Troubleshooting

- **401 Unauthorized**: Check that your token is valid and included in the Authorization header as `Bearer your-token`
- **403 Forbidden**: For admin summary routes, verify your authenticated user has `is_admin=1` in the database
- **Validation Errors**: Ensure required fields are present in POST/PUT requests
- **Connection Refused**: Confirm your development server is running on the correct port

## Example Workflow

1. **Get staff token** using admin credentials via `/api/staff/login`
2. **Create a test staff user** via `/api/admin/staff` (optional)
3. **Test open endpoints** (staff list, pending attendances, approve/reject)
4. **Test protected endpoints** (staff summary, user summary, my attendance) using the token from step 1