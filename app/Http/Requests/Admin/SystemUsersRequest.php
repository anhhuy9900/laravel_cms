<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SystemUsersRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'role_id' => 'required',
            'username'  => 'required|min:5|unique:system_users',
            'email' => 'required|email|unique:system_users',
            'password'  => 'required|min:6'
        ];
    }
}
