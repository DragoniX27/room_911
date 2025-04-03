<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Events\LoguinAttemptEvent;
use Illuminate\Support\Facades\Log;

class LoginValidation
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Validate if the user is active
        $user = \App\Models\User::firstWhere(['email' => $request->email, 'status' => 'active']);

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (session()->has('login_attempt_executed')) { // condition to avoid duplicating the event by fortify
                    event(new LoguinAttemptEvent($user->id, $user->email, $request->ip(), $request->header('User-Agent'), 'success'));
                }
                session(['login_attempt_executed' => true]);
                return $user;
            }
            event(new LoguinAttemptEvent($user->id, $user->email, $request->ip(), $request->header('User-Agent'), 'failed'));
        }

        throw ValidationException::withMessages([
            Fortify::username() => ['Access denied.'],
        ]);
    }
}
