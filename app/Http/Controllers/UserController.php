<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllUserRequest;
use App\Http\Resources\ActiveUserResource;
use App\Http\Responses\BaseResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController
{
    /**
     * @authenticated
     */
    public function allUsers(AllUserRequest $request)
    {
        $userId = Auth::user()->id;
        $activeUsers = User::whereNotNull('email_verified_at')
            ->whereNot('id', $userId);
        if ($request->name_filter != null) {
            $activeUsers = $activeUsers->where('name', 'LIKE', '%'.$request->name_filter.'%');
        }
        $activeUsers = $activeUsers->paginate($request->per_page);

        return BaseResponse::success(ActiveUserResource::collection($activeUsers)->toArray($request));
    }
}
