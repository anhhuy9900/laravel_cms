<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PropertyDataRequest extends Request
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
            'type_config' => 'required',
            'title_config' => 'required',
            'key_config'  => 'required',
           // 'value_config'  => 'required'
        ];
    }
}
