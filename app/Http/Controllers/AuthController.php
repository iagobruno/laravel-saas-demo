<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'/* , 'unique:accounts,email' */]
        ]);

        $account = Account::firstOrCreate([
            'email' => $data['email'],
        ]);

        Auth::login($account);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
