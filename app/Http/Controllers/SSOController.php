<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SSOController extends Controller
{
    public function getLogin(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => env('SSO_CLIENT_ID'),
            'redirect_uri' => env('SSO_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => env('SSO_SCOPE'),
            'state' => $state,
        ]);

        return redirect('http://localhost:8080/oauth/authorize?' . $query);
    }

    public function getCallback(Request $request)
    {
        $state =  $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state == $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post(
            'http://localhost:8080/oauth/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => env('SSO_CLIENT_ID'),
                'client_secret' => env('SSO_CLIENT_SECRET'),
                'redirect_uri' => env('SSO_REDIRECT_URI'),
                'code' => $request->code
            ]
        );
        $request->session()->put('is_login', true);
        $request->session()->put($response->json());

        return redirect('/');
    }

    public function getUser(Request $request)
    {
        $access_token = $request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->get(env('SSO_PROVIDER_URL') . '/api/user');

        return $response->json();
    }
}
