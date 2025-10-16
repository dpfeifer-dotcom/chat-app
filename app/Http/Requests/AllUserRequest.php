<?php

namespace App\Http\Requests;

class AllUserRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name_filter' => 'string|min:3',
            'per_page' => 'integer|min:1',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        $data['per_page'] = $data['per_page'] ?? 10;

        return $data;
    }
}
