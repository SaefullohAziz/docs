<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivity extends FormRequest
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
            'date' => ['required'],
            'until_date' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') != 'Kunjungan_industri';
                    }
                }),
            ],
            'submission_letter' => ['required', 'mimes:jpeg,png,jpg,pdf'],
            'amount_of_student' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') != 'Kunjungan_industri';
                    }
                }),
                'numeric'
            ],
            'amount_of_teacher' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Kunjungan_industri';
                    }
                }),
                'numeric'
            ],
            'amount_of_acp_student' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Kunjungan_industri';
                    }
                }),
                'numeric'
            ],
            'participant' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Kunjungan_industri';
                    }
                }),
                'mimes:xls,xlsx,xlsm',
            ],
            'activty' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Axioo_Mengajar';
                    }
                }),
            ],
            'period' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') != 'Kunjungan_industri';
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
            ];
            $rules = array_merge($rules, $addonRules);
        }
        if (auth()->guard('admin')->check()) {
            $addonRules = [
                'school_id' => ['required']
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
            'type.required' => 'The type field is required.',
            'date.required' => 'The date field is required.'
        ];
    }
}
