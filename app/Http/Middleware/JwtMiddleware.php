<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari header Authorization: Bearer <token>
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Authorization Token not found'], 401);
        }

        // Ambil URL Auth Service dari .env (default ke localhost:8001)
        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://127.0.0.1:8001');

        try {
            // Lakukan HTTP GET ke Auth Service untuk introspeksi token (Opsi A)
            $response = Http::timeout(5)->withToken($token)->get("{$authServiceUrl}/api/auth/me");

            if ($response->successful()) {
                $userData = $response->json();
                
                // Simpan user_id ke dalam request agar bisa digunakan oleh Controller
                $request->merge([
                    'auth_user_id' => $userData['id'] ?? null,
                    'auth_user_roles' => $userData['roles'] ?? ['User'], // Menyimpan Global Roles dari Spatie
                ]);
            } else {
                // Jika response bukan 200 (mungkin 401 karena expired/blacklist)
                return response()->json([
                    'success' => false, 
                    'message' => 'Token is Invalid, Expired, or Blacklisted'
                ], 401);
            }
        } catch (\Exception $e) {
            // Jika Auth Service sedang mati atau tidak bisa dihubungi
            return response()->json([
                'success' => false, 
                'message' => 'Internal Error: Could not connect to Auth Service'
            ], 500);
        }
        
        return $next($request);
    }
}
