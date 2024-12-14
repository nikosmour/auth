<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Loop through all available guards and attempt login
        foreach (config('auth.guards') as $guard => $temp) {
            if ($guard !== 'sanctum' && Auth::guard($guard)->attempt($credentials)) {
                // Optionally, return user data or a token if needed
                /** @var User $user */
                $user = Auth::guard($guard)->user();
                $hashCode = Str::random(32);
                $data = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'guard' => $guard,
                ];

// Store in cache
                Cache::put($hashCode, $data, now()->addMinutes(30));
                return response()->json([
                    "hashCode" => $hashCode,
                    "user"=>[
                    'email' => $user->email,
                    'name' => $user->name,
                    'status' => $user->status,
                    'a_m' => $user->a_m ?? null,
                    "academic_id" => $user->academic_id ?? null,
                    'is_active' => $user->is_active ?? true,
                    'department' => $user->cardApplicant()->withOnly('departmentRelation')->first()?->department ?? null,
                    'guard' => $guard,
                        ]
                ]);
            }
        }
        return response()->json([
            'error' => 'invalid_credentials',
        ]);
    }
}
