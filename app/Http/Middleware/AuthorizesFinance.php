<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait AuthorizesFinance
{
    protected function authorizeFinance($user, string $permission = 'view')
    {
        if (! $user) {
            abort(401, 'Unauthorized.');
        }

        if ($permission === 'edit') {
            $role = strtolower(trim((string) ($user->role ?? '')));

            if ($role !== 'finance admin') {
                Log::info('AuthorizesFinance', ['result' => 'forbidden', 'permission' => $permission, 'role' => $role, 'user_id' => $user->id]);
                abort(403, 'Unauthorized.');
            }

            Log::info('AuthorizesFinance', ['result' => 'edit allowed', 'role' => $role, 'user_id' => $user->id]);
        }

        Log::info('AuthorizesFinance', ['result' => 'view allowed', 'user_id' => $user->id]);
    }
}
