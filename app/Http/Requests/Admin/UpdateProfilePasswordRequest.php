<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePasswordRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|alphaNum|min:6|max:50',
            'password' => 'required|alphaNum|confirmed|min:6|max:30',
            'password_confirmation' => 'required|alphaNum|min:6|max:30'
        ];
    }
}