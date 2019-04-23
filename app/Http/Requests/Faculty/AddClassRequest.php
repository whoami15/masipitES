<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class AddClassRequest extends FormRequest
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
            'subject' => 'required|numeric|exists:tbl_subjects,id',
            'grade_level' => 'required|numeric|exists:tbl_grade_level,id',
            'credits' => 'required|numeric',
            'weeks' => 'required|numeric',
            'school_year' => 'required',
            'description' => 'required|min:2'
        ];
    }
}
