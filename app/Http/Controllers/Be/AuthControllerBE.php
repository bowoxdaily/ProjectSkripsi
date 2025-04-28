<?php

namespace App\Http\Controllers\Be;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthControllerBE extends Controller
{
    public function register(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:siswa', // Ensure only siswa role can register via this endpoint
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Generate token for the new user (optional)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token, // Include token if using sanctum/passport
                ]
            ], 201);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate login data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Attempt authentication without using session
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = Auth::user();


        // Return token without manipulating session
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'redirect_to' => $this->getRedirectRouteForRole($user->role)
            ]
        ], 200);
    }

    /**
     * Get the redirect route based on user role.
     *
     * @param  string  $role
     * @return string
     */
    private function getRedirectRouteForRole($role)
    {
        switch ($role) {
            case 'siswa':
                return route('siswa.dashboard');
            case 'guru':
                return route('guru.dashboard');
            case 'operator':
                return route('operator.dashboard');
            default:
                return route('home');
        }
    }

    /**
     * Logout the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            // Hapus semua token personal access token (jika pakai Sanctum/Passport)
            $request->user()->tokens()->delete();

            // Hapus remember token
            $request->user()->setRememberToken(null);
            $request->user()->save();
        }

        // Logout session web
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus cookie remember_me
        Cookie::queue(Cookie::forget(Auth::getRecallerName()));

        // JSON response untuk API
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        // Redirect ke halaman login untuk pengguna web
        return redirect('/login');
    }
}
