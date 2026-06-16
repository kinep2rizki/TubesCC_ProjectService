<?php

namespace App\Traits;

use App\Models\CommunityMember;
use Illuminate\Http\Request;

trait CommunityAuthorization
{
    /**
     * Authorize a user against a specific community based on allowed roles.
     *
     * @param int $communityId
     * @param array $allowedRoles Array of roles (e.g. ['Owner', 'Moderator'])
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeCommunityAccess($communityId, array $allowedRoles = ['Owner'])
    {
        $request = request();
        $globalRoles = $request->auth_user_roles ?? [];
        $userId = $request->auth_user_id;

        // 1. Super Admin bypass (Global Role)
        if (in_array('Super Admin', $globalRoles)) {
            return;
        }

        // 2. Fetch the local role
        $member = CommunityMember::where('community_id', $communityId)
            ->where('user_id', $userId)
            ->first();

        // 3. Check if user is a member and has an allowed role
        if (!$member || !in_array($member->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki hak akses yang cukup di komunitas ini.');
        }
    }
}
