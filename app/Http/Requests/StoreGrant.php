<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGrant extends FormRequest
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
                Rule::requiredIf(auth()->guard('admin')->check()),
            ],
            'requirement' => ['required'],
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
            'terms' => ['required'],
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
        return $rules;
    }
}
