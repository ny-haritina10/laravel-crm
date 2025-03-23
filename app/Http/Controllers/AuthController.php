<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
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
            // Call the CRM API for authentication
            $response = Http::post($this->crmApiUrl . '/api/auth/login_api', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $userData = $response->json();

                // Store user data in session
                session([
                    'user_id' => $userData['userId'],
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'roles' => $userData['roles'],
                    'token' => $userData['token'],
                    'is_logged_in' => true
                ]);

                // Check if user has manager role
                $isManager = in_array('ROLE_MANAGER', $userData['roles']);
                
                // Redirect based on role
                if ($isManager) {
                    return redirect()->route('dashboard.manager');
                } else {
                    return redirect()->route('dashboard');
                }
            }

            // Handle different error responses
            if ($response->status() === 401) {
                return back()->withErrors(['auth' => 'Invalid credentials']);
            } else {
                return back()->withErrors(['auth' => 'Authentication failed: ' . $response->body()]);
            }
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