<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $response = Http::post($this->crmApiUrl . '/api/auth/login_api', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $userData = $response->json();

                // Check if required data exists in the response
                if (isset($userData['userId']) && isset($userData['token'])) {
                    session([
                        'user_id' => $userData['userId'],
                        'username' => $userData['username'],
                        'email' => $userData['email'] ?? null,
                        'roles' => $userData['roles'] ?? [],
                        'token' => $userData['token'],
                        'is_logged_in' => true,
                    ]);

                    $isManager = in_array('ROLE_MANAGER', $userData['roles'] ?? []);
                    return $isManager
                        ? redirect()->route('dashboard.manager')
                        : redirect()->route('dashboard');
                }

                return back()->withErrors(['auth' => 'Invalid response from CRM API']);
            }

            return back()->withErrors(['auth' => $response->status() === 401
                ? 'Invalid credentials'
                : 'Authentication failed: ' . $response->body()]);
        } catch (\Exception $e) {
            return back()->withErrors(['auth' => 'Error connecting to CRM: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}