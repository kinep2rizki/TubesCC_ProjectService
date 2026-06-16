<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Fetch detailed profiles of users from Auth Service via Batch API.
     *
     * @param array $userIds
     * @return array
     */
    public static function getUsersBatch(array $userIds)
    {
        if (empty($userIds)) {
            return [];
        }

        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://127.0.0.1:8001');

        try {
            $response = Http::timeout(5)->post("{$authServiceUrl}/api/auth/users/batch", [
                'ids' => array_values(array_unique($userIds))
            ]);

            if ($response->successful()) {
                // Auth Service is expected to return an array of user objects.
                $users = $response->json(); 
                
                // Map the array by 'id' for O(1) fast lookup during stitching
                $mappedUsers = [];
                foreach ($users as $user) {
                    if (isset($user['id'])) {
                        $mappedUsers[$user['id']] = $user;
                    }
                }
                
                return $mappedUsers;
            }
            
            Log::error('Auth Service /users/batch returned error', [
                'status' => $response->status(), 
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Auth Service connection failed', ['error' => $e->getMessage()]);
        }

        // Return empty array gracefully if it fails, so we don't break the whole app
        return [];
    }

    /**
     * Fetch a single user profile.
     *
     * @param mixed $userId
     * @return array|null
     */
    public static function getUser($userId)
    {
        $users = self::getUsersBatch([$userId]);
        return $users[$userId] ?? null;
    }

    /**
     * Find user by email or create new in Auth Service.
     */
    public static function findOrCreateUser($email, $name)
    {
        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://127.0.0.1:8001');
        
        try {
            $response = Http::timeout(5)->post("{$authServiceUrl}/api/auth/users/find-or-create", [
                'email' => $email,
                'name' => $name
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Auth Service find-or-create failed', ['error' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Find user by email in Auth Service.
     */
    public static function findUserByEmail($email)
    {
        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://127.0.0.1:8001');
        
        try {
            $response = Http::timeout(5)->post("{$authServiceUrl}/api/auth/users/find-by-email", [
                'email' => $email
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Auth Service find-by-email failed', ['error' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Search users by keyword in Auth Service, returning array of user IDs.
     */
    public static function searchUsers($keyword)
    {
        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://127.0.0.1:8001');
        
        try {
            $response = Http::timeout(5)->post("{$authServiceUrl}/api/auth/users/search", [
                'keyword' => $keyword
            ]);
            
            if ($response->successful()) {
                return $response->json(); // Array of IDs
            }
        } catch (\Exception $e) {
            Log::error('Auth Service search failed', ['error' => $e->getMessage()]);
        }
        return [];
    }
}
