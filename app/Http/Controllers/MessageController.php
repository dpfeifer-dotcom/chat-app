<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\MakeMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Responses\BaseResponse;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController
{
    /**
     * @authenticated
     */
    public function makeMessage(MakeMessageRequest $request)
    {
        $user = Auth::user();

        if ($user->id === $request->user_id) {
            return BaseResponse::error('Invalid user', 400);
        }

        $toUser = User::findOrFail($request->user_id);

        /** @var User $user */
        if (! $user->isFriendWith($toUser)) {
            return BaseResponse::error('Invalid user', 400);
        }

        Message::create([
            'from_user_id' => $user->id,
            'to_user_id' => $toUser->id,
            'message' => $request->message,
        ]);

        return BaseResponse::success();
    }

    /**
     * @authenticated
     */
    public function getMessages(GetMessagesRequest $request)
    {
        $user = Auth::user();

        if ($user->id === $request->user_id) {
            return BaseResponse::error('Invalid user', 400);
        }

        $friend = User::findOrFail($request->user_id);

        $messages = Message::where('from_user_id', $user->id)
            ->where('to_user_id', $friend->id)
            ->get();

        return BaseResponse::success(['messages' => MessageResource::collection($messages)->toArray(request())]);
    }
}
