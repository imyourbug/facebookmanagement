<?php

namespace App\Http\Requests\Admin\Accounts;

use App\Http\Requests\BaseRequest;

class UpdateAccountRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'password' => 'nullable|string',
            'delay' => 'required|integer',
            'limit' => 'required|integer',
            'expire' => 'required|integer',
            'role' => 'required|in:0,1',
        ];
    }
}
