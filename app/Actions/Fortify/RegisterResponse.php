<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an authenticated user response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        
        if ($user && $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Customer registration - redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials or Google.');
    }
}