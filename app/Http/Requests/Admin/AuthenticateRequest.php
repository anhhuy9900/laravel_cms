<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AuthenticateRequest extends Request
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
            'username' => 'required|exists:system_users|admin_check_valid_user',
            'password'  => 'required'
        ];
    }

    public function messages(){
        return [
            'username.admin_check_valid_user' => 'The username and password invalid'
        ];
    }
}
