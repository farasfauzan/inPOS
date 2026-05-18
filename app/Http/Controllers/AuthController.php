<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Handle login - both GET (show form) and POST (process login)
     */
    public function handleLogin(Request $request)
    {
        // GET request - show login page
        if ($request->isMethod('get')) {
            return view('pages.login');
        }

        // POST request - process login
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Username atau password salah'
            ], 401);
        }

        Session::put('users_id', $user->users_id);
        Session::put('username', $user->username);
        Session::put('full_name', $user->full_name);
        Session::put('role', $user->role);

        return response()->json([
            'success' => true,
            'redirect' => '/dashboard'
        ]);
    }

    /**
     * Show login page (alias)
     */
    public function showLogin()
    {
        return view('pages.login');
    }

    /**
     * Handle login request (alias for backward compatibility)
     */
    public function login(Request $request)
    {
        return $this->handleLogin($request);
    }

    /**
     * Handle logout request.
     */
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    /**
     * Check session status.
     */
    public function session()
    {
        if (Session::has('users_id')) {
            return response()->json([
                'loggedIn' => true,
                'user' => [
                    'users_id' => Session::get('users_id'),
                    'username' => Session::get('username'),
                    'fullName' => Session::get('full_name'),
                    'role' => Session::get('role'),
                ]
            ]);
        }

        return response()->json(['loggedIn' => false]);
    }
}