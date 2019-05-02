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
            'pic_name' => ['required'],
            'pic_position' => ['required'],
            'pic_phone_number' => [
                'required',
                'numeric',
            ],
            'pic_email' => [
                'required',
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
            'proposal' => ['required'],
        ];
        if ( ! empty($this->get('reference'))) {
            if (in_array('Dealer', $this->get('reference'))) {
                $rules['dealer_phone_number'] = [
                    'required',
                    'numeric',
                ];
                $rules['dealer_email'] = [
                    'required',
                    'email',
                    'different:school_email',
                    'different:headmaster_email',
                    'different:pic_email'
                ];
            }
        }
        if ($this->isMethod('put')) {
            $rules['name'] = [
                'required',
            ];
        }
        return $rules;
    }
}
