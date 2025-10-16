<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetFriendShipRequest;
use App\Http\Responses\BaseResponse;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController
{
    /**
     * @authenticated
     */
    public function setFriend(SetFriendShipRequest $request)
    {
        $userId = Auth::user()->id;

        if ($userId === $request->user_id) {
            return BaseResponse::error('Invalid user', 400);
        }

        $toUser = User::findOrFail($request->user_id);
        if (! $toUser->email_verified_at) {
            return BaseResponse::error('Inactive user', 400);
        }

        Friendship::create([
            'from_user_id' => $userId,
            'to_user_id' => $request->user_id,
        ]);

        return BaseResponse::success();
    }
}
