<?php

namespace App\Http\Requests;

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
        $rules = [
            'name' => ['required'],
            'nickname' => ['required'],
            'province' => ['required'],
            'school_year' => ['required'],
            'nisn' => ['required', 'digits:10', 'unique:students,nisn'],
            'department' => ['required'],
            'email' => ['required', 'email'],
            'gender' => ['required'],
            'grade' => ['required'],
            'generation' => ['required'],
            'father_name' => ['required'],
            'father_education' => ['required'],
            'father_earning' => ['required'],
            'father_earning_nominal' => ['required', 'numeric'],
            'mother_name' => ['required'],
            'mother_education' => ['required'],
            'mother_earning' => ['required'],
            'mother_earning_nominal' => ['required', 'numeric'],
            'trustee_name' => ['required'],
            'trustee_education' => ['required'],
            'economy_status' => ['required'],
            'religion' => ['required'],
            'blood_type' => ['required'],
            'special_need' => ['required'],
            'mileage' => ['required'],
            'distance' => ['required', 'numeric'],
            'diploma_number' => ['required'],
            'height' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'child_order' => ['required'],
            'sibling_number' => ['required'],
            'stepbrother_number' => ['required'],
            'step_sibling_number' => ['required'],
            'dateofbirth' => ['required'],
            'address' => ['required'],
            'father_address' => ['required'],
            'trustee_address' => ['required'],
            'phone_number' => ['required', 'numeric', 'digits_between:8,11'],
            'computer_basic_score' => ['required', 'integer'],
            'intelligence_score' => ['required', 'integer'],
            'reasoning_score' => ['required', 'integer'],
            'analogy_score' => ['required', 'integer'],
            'numerical_score' => ['required', 'integer'],
            'terms' => ['required']
        ];
        if (auth()->guard('admin')->check()) {
            $addonRules = [
              'school_id' => ['required']  
            ];
            $rules = array_merge($rules, $addonRules);
        }
        if ($this->isMethod('put')) {
            $addonRules = [
                'nisn' => ['required', 'digits:10']
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}