<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class LearningMaterialRequest extends FormRequest
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
            'title' => 'required|min:2',
            'doc_file' => 'required|mimetypes:.csv,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
            'subject' => 'required|numeric|exists:tbl_class,subject,user_id,'.Auth::user()->id,
            'grade_level' => 'required|numeric|exists:tbl_class,grade_level,user_id,'.Auth::user()->id,
            'description' => 'required|min:2'
        ];
    }

    public function messages()
    {
        return [
            'doc_file.mimetypes'  => 'Invalid or not supported file.',
        ];
    }
}
