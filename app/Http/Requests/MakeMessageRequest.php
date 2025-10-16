<?php

namespace App\Http\Requests;

class MakeMessageRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:255',
        ];
    }
}
