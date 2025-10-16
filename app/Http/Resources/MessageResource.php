<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'from_user' => [
                'name' => $this->fromUser->name,
            ],
            'to_user' => [
                'name' => $this->toUser->name,
            ],
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
