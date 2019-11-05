<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJoinedSchoolSet extends FormRequest
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
        $rules = [
            'department' => ['required'],
            'other_department' => Rule::requiredIf($this->get('department')=='Lain-Lain'),
            'name' => ['required'],
            'position' => ['required'],
            'phone_number' => [
                'required',
                'numeric',
            ],
            'email' => [
                'required',
                'email',
                'unique:schools,school_email',
                'unique:schools,headmaster_email',
                'unique:schools,dealer_email',
                'unique:pics,email',
                'unique:teachers,email',
                'unique:students,email',
            ],
        ];
        return $rules;
    }
}
