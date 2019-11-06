<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchool extends FormRequest
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
            'type' => ['required'],
            'name' => [
                'required',
                'unique:schools,name'
            ],
            'address' => ['required'],
            'province' => ['required'],
            'regency' => ['required'],
            'police_number' => ['required'],
            'since' => [
                'required',
                'digits:4'
            ],
            'school_phone_number' => [
                'required',
                'numeric',
            ],
            'school_email' => [
                'required',
                'email',
                'different:headmaster_email',
                'different:pic_email',
                'different:dealer_email'
            ],
            'school_web' => [
                'required',
                'url'
            ],
            'total_student' => [
                'required',
                'numeric',
            ],
            'department' => [
                'required',
                'array',
                'min:1'
            ],
            'department.*' => [
                'required',
                'min:1'
            ],
            'iso_certificate' => ['required'],
            'mikrotik_academy' => ['required'],
            'headmaster_name' => ['required'],
            'headmaster_phone_number' => [
                'required',
                'numeric',
            ],
            'headmaster_email' => [
                'required',
                'email',
                'different:school_email',
                'different:pic_email',
                'different:dealer_email'
            ],
            'pic_name' => [
                Rule::requiredIf($this->isMethod('post')),
            ],
            'pic_position' => [
                Rule::requiredIf($this->isMethod('post')),
            ],
            'pic_phone_number' => [
                Rule::requiredIf($this->isMethod('post')),
                'numeric',
            ],
            'pic_email' => [
                Rule::requiredIf($this->isMethod('post')),
                'email',
                'different:school_email',
                'different:headmaster_email',
                'different:dealer_email'
            ],
            'pic.*.name' => [
                Rule::requiredIf($this->isMethod('put')),
            ],
            'pic.*.position' => [
                Rule::requiredIf($this->isMethod('put')),
            ],
            'pic.*.phone_number' => [
                Rule::requiredIf($this->isMethod('put')),
                'numeric',
            ],
            'pic.*.email' => [
                Rule::requiredIf($this->isMethod('put')),
                'email',
                'different:school_email',
                'different:headmaster_email',
                'different:dealer_email'
            ],
            'reference' => [
                'required',
                'array',
                'min:1'
            ],
            'reference.*' => [
                'required',
                'min:1'
            ],
            'dealer_name' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('reference'))) {
                        return in_array('Dealer', $this->get('reference'));
                    }
                }),
            ],
            'dealer_phone_number' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('reference'))) {
                        return in_array('Dealer', $this->get('reference'));
                    }
                }),
                // 'numeric'
            ],
            'dealer_email' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('reference'))) {
                        return in_array('Dealer', $this->get('reference'));
                    }
                }),
                // 'email',
                'different:school_email',
                'different:headmaster_email',
                'different:pic_email'
            ],
            'proposal' => ['required'],
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required'],
                'school_email' => [
                    'required',
                    'email',
                    'different:headmaster_email',
                    // 'different:pic.0.email',
                    'different:dealer_email'
                ],
                'headmaster_email' => [
                    'required',
                    'email',
                    'different:school_email',
                    // 'different:pic.*.email',
                    'different:dealer_email'
                ],
                'dealer_email' => [
                    Rule::requiredIf(function () {
                        if ( ! empty($this->get('reference'))) {
                            return in_array('Dealer', $this->get('reference'));
                        }
                    }),
                    // 'email',
                    'different:school_email',
                    'different:headmaster_email',
                    // 'different:pic_email'
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
            'pic.*.name.required' => 'Each pic name field is required.',
            'pic.*.position.required' => 'Each pic position field is required.',
            'pic.*.phone_number.required' => 'Each pic phone number field is required.',
            'pic.*.email.required' => 'Each pic email field is required.',
        ];
    }
}
