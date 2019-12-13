<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubsidy extends FormRequest
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
            'school_id' => [
                Rule::requiredIf(function () {
                    return auth()->guard('admin')->check();
                }),
            ],
            'type' => ['required'],
            'student_id' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Student Starter Pack (SSP)';
                    }
                }),
                'array',
                'min:1'
            ],
            'student_id.*' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Student Starter Pack (SSP)';
                    }
                }),
                'min:1'
            ],
            'student_year' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Axioo Next Year Support';
                    }
                }),
            ],
            'pic_name' => [
                'required',
            ],
            'pic_position' => [
                'required',
            ],
            'pic_phone_number' => [
                'required',
                'numeric', 
                'digits_between:8,11'
            ],
            'pic_email' => [
                'required',
                'email',
            ],
        ];
        if ($this->isMethod('post')) {
            $addonRules = [
                'pic' => ['required'],
                'pic_name' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('pic'))) {
                            return $this->get('pic') == 1;
                        }
                    }),
                ],
                'pic_position' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('pic'))) {
                            return $this->get('pic') == 1;
                        }
                    }),
                ],
                'pic_phone_number' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('pic'))) {
                            return $this->get('pic') == 1;
                        }
                    }),
                    'numeric', 
                    'digits_between:8,11'
                ],
                'pic_email' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('pic'))) {
                            return $this->get('pic') == 1;
                        }
                    }),
                    'email',
                ],
                'qty' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('type'))) {
                            return $this->get('type') == 'Student Starter Pack (SSP)' OR $this->get('type') == 'Notebook Assembling & Troubleshooting Training (NATT)';
                        }
                    }),
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
            'student_id.required' => 'Choose at least one student.',
            'student_id.*.required' => 'Choose at least one student.',
        ];
    }
}
