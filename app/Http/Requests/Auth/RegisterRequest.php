<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:tbl_users,email',
            'username' => 'required|alphaNum|max:255|unique:tbl_users,username',
            'password' => 'required|alphaNum|confirmed|min:6|max:20',
            'first_name' => 'required|min:1|max:50',
            'middle_name' => 'required|min:1|max:50',
            'last_name' => 'required|min:1|max:50',
            'role' => 'required',
            'id_no' => 'required_if:role,==,1',
            'grade_level' => 'exists:tbl_grade_level,id|required_if:role,==,1',
            'position' => 'exists:tbl_position,position|required_if:role,==,2',
            'department' => 'exists:tbl_department,department|required_if:role,==,2',
            'security_key' => 'required_if:role,==,2',
            //'g-recaptcha-response' => 'required|captcha',
        ];
    }


    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha'  => 'Captcha error! try again later or contact site admin.',
        ];
    }
}
