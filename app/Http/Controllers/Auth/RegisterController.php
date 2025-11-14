<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HbsClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create default classes for the new user
        $this->createDefaultClasses($user);

        Auth::login($user);

        return redirect('/');
    }

    private function createDefaultClasses(User $user)
    {
        $classes = [
            ['code' => 'STRAT', 'name' => 'Strategy'],
            ['code' => 'FIN', 'name' => 'Finance'],
            ['code' => 'FRC', 'name' => 'Financial Reporting and Control'],
            ['code' => 'LEAD', 'name' => 'Leadership'],
            ['code' => 'TOM', 'name' => 'Technology and Operations Management'],
            ['code' => 'MAR', 'name' => 'Marketing'],
        ];

        foreach ($classes as $class) {
            HbsClass::create([
                'user_id' => $user->id,
                'code' => $class['code'],
                'name' => $class['name'],
            ]);
        }
    }
}
