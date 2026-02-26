<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * We protect all methods except login using the auth:api middleware.
     */
    public function __construct()
    {
        // For Laravel 11/12 we can define middleware in routes/route files, but we can also use routing here:
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $guard = $this->getGuard();

        if (! $token = auth($guard)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $guard);
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        $guard = $this->getGuard();
        return response()->json(auth($guard)->user());
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        $guard = $this->getGuard();
        auth($guard)->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        $guard = $this->getGuard();
        return $this->respondWithToken(auth($guard)->refresh(), $guard);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token, string $guard): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($guard)->factory()->getTTL() * 60
        ]);
    }

    /**
     * Determine which guard to use based on the request path.
     */
    protected function getGuard(): string
    {
        if (request()->is('api/admin/*') || request()->is('api/admin')) {
            return 'admin';
        }

        // Check current authenticated guard if any
        if (auth('admin')->check()) {
            return 'admin';
        }

        return 'api';
    }
}
