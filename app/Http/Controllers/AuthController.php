<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     * Route pattern determines which guard to use (admin vs api/employee).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $guard = $this->getGuardFromRoute();

        if (! $token = auth($guard)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $guard);
    }

    /**
     * Get the authenticated user.
     * Tries the admin guard first, then the api (employee) guard.
     * This is necessary because /api/me is shared between admins and employees.
     */
    public function me(): JsonResponse
    {
        // Try admin guard first
        if ($adminUser = auth('admin')->user()) {
            return response()->json($adminUser);
        }

        // Fall back to api (employee) guard
        $user = auth('api')->user();

        if ($user) {
            $user->load(['employee.department', 'employee.designation']);
        }

        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        // Invalidate whichever guard's token is active
        if (auth('admin')->check()) {
            auth('admin')->logout();
        } else {
            auth('api')->logout();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        if (auth('admin')->check()) {
            return $this->respondWithToken(auth('admin')->refresh(), 'admin');
        }

        return $this->respondWithToken(auth('api')->refresh(), 'api');
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token, string $guard): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth($guard)->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Determine guard based solely on the route URL pattern.
     * Only used during login — before any JWT token exists.
     */
    protected function getGuardFromRoute(): string
    {
        if (request()->is('api/admin*')) {
            return 'admin';
        }

        return 'api';
    }
}
