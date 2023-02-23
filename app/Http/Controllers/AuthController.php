<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        return redirect(env('SSO_PROVIDER_URL'));
    }
}
