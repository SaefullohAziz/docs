<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTraining extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'implementation' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Basic (ToT)' || $this->get('type') == 'Adobe Photoshop';
                    }
                }),
            ],
            'approval_code' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Basic (ToT)';
                    }
                }),
            ],
            'room_type' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Basic (ToT)';
                    }
                }),
                'array',
                'min:1'
            ],
            'room_type.*' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Basic (ToT)';
                    }
                }),
                'min:1'
            ],
            'room_size' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Basic (ToT)';
                    }
                }),
            ],
            'has_asset' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') == 'Elektronika Dasar';
                    }
                }),
            ],
            'participant_id' => [
                'required',
                'array',
                'min:2'
            ],
            'participant_id.*' => [
                'required',
                'min:1'
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
        if (! $request->prices_accept){
            $addonRules = [
                'participant_id' => [
                    'max:2'
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
            'participant_id.required' => 'Participant is required for every training registration.',
            'participant_id.min' => 'Choose at least two participant.',
        ];
    }
}
