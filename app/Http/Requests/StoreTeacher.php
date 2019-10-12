<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacher extends FormRequest
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
        $teacher = $this->route('teacher');
        $rules = [
            'school_id' => Rule::requiredIf(auth()->guard('admin')->check()),
            'name' => ['required'],
            'nip' => ['numeric', 'digits_between:14,20'],
            'gender' => ['required'],
            'position' => ['required'],
            'teaching_status' => ['required'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'date_of_birth' => ['required'],
            'phone_number' => ['required', 'numeric', 'digits_between:8,11', 'unique:teachers,phone_number'],
            'terms' => ['required']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'email' => [
                    'required', 
                    'email', 
                    Rule::unique('teachers')->ignore($teacher),
                ],
                'phone_number' => [
                    'required', 
                    'numeric', 
                    'digits_between:8,11', 
                    Rule::unique('teachers')->ignore($teacher),
                ],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'school_id.required' => 'The school field is required.',
        ];
    }
}
