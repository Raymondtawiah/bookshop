<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

trait AuthorizesFinance
{
    protected function authorizeFinance($user, string $permission = 'view')
    {
        if (! $user) {
            abort(401, 'Unauthorized.');
        }

        if ($user->is_admin) {
            return true;
        }

        $role = $user->role;

        if ($role === 'Finance Admin') {
            return true;
        }

        if ($role === 'Finance Member' && $permission === 'view') {
            return true;
        }

        abort(403, 'Unauthorized.');
    }
}
