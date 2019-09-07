<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudent extends FormRequest
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
        $student = $this->route('student');
        $rules = [
            'name' => ['required'],
            'nickname' => ['required'],
            'province' => ['required'],
            'nisn' => ['required', 'digits:10', 'unique:students,nisn'],
            'email' => ['required', 'email', 'unique:students,email'],
            'gender' => ['required'],
            'father_name' => [],
            'father_education' => [],
            'father_earning' => [],
            'father_earning_nominal' => ['numeric'],
            'mother_name' => ['required'],
            'mother_education' => [],
            'mother_earning' => [],
            'mother_earning_nominal' => ['numeric'],
            'trustee_name' => [],
            'trustee_education' => [],
            'economy_status' => [],
            'religion' => ['required'],
            'blood_type' => ['required'],
            'special_need' => ['required'],
            'mileage' => [],
            'distance' => ['numeric'],
            'diploma_number' => [],
            'height' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'child_order' => [],
            'sibling_number' => [],
            'stepbrother_number' => [],
            'step_sibling_number' => [],
            'dateofbirth' => ['required'],
            'address' => ['required'],
            'father_address' => [],
            'trustee_address' => [],
            'phone_number' => ['required', 'numeric', 'digits_between:8,11', 'unique:students,phone_number'],
            'computer_basic_score' => ['integer'],
            'intelligence_score' => ['integer'],
            'reasoning_score' => ['integer'],
            'analogy_score' => ['integer'],
            'numerical_score' => ['integer'],
            'terms' => ['required']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'nisn' => [
                    'required', 
                    'digits:10',
                    Rule::unique('students')->ignore($student),
                ],
                'email' => [
                    'required', 
                    'email', 
                    Rule::unique('students')->ignore($student),
                ],
                'phone_number' => [
                    'required', 
                    'numeric', 
                    'digits_between:8,11', 
                    Rule::unique('students')->ignore($student),
                ],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}