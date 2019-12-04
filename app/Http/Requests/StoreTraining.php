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
            'type' => ['required'],
            'approval_code' => [
                Rule::requiredIf($this->get('type') == 'Basic (ToT)'),
            ],
            'room_type' => [
                Rule::requiredIf($this->get('type') == 'Basic (ToT)'),
                'array',
                'min:1'
            ],
            'room_type.*' => [
                Rule::requiredIf($this->get('type') == 'Basic (ToT)'),
                'min:1'
            ],
            'room_size' => [
                Rule::requiredIf($this->get('type') == 'Basic (ToT)'),
            ],
            'has_asset' => [
                Rule::requiredIf($this->get('type') == 'Elektronika Dasar'),
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
                    Rule::requiredIf($this->get('pic') == 1),
                ],
                'pic_position' => [
                    Rule::requiredIf($this->get('pic') == 1),
                ],
                'pic_phone_number' => [
                    Rule::requiredIf($this->get('pic') == 1),
                    'numeric', 
                    'digits_between:8,11'
                ],
                'pic_email' => [
                    Rule::requiredIf($this->get('pic') == 1),
                    'email',
                ],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        if (auth()->guard('web')->check()) {
            $setting = collect(json_decode(setting('training_settings')))->where('name', $this->get('type'))->first();
            $quotaSetting = \App\Training::quotaSetting($setting, auth()->user());
            if (setting($setting->limiter_slug) == 'Quota' xor setting($setting->limiter_slug) == 'Both') {
                $max = setting($setting->quota_limit_slug)-$quotaSetting['waitedQuota'];
                if ( ! empty($quotaSetting['levels']) || ! empty($quotaSetting['departments'])) {
                    if ( ! empty($quotaSetting['levelLimitCount']) xor ! empty($quotaSetting['departmentLimitCount'])) {  
                        if ( ! empty($quotaSetting['levelLimitCount'])) {
                            $max = $quotaSetting['levelLimitCount']-$quotaSetting['waitedQuota'];
                        } elseif ( ! empty($quotaSetting['departmentLimitCount'])) {
                            $max = $quotaSetting['departmentLimitCount']-$quotaSetting['waitedQuota'];
                        }
                    } elseif ( ! empty($quotaSetting['levelLimitCount']) && ! empty($quotaSetting['departmentLimitCount'])) {
                        $max = $quotaSetting['departmentLimitCount']-$quotaSetting['waitedQuota'];
                    }
                }
            }
            if ( ! empty($max)) {
                $addonRules = [
                    'participant_id' => [
                        'required',
                        'array',
                        'min:' . ($max < 2 ? 1 : 2),
                        'max:' . $max
                    ],
                ];
                $rules = array_merge($rules, $addonRules);
            }
            if (setting($setting->more_participant_slug) == '' || setting($setting->more_participant_slug) == null) {
                if ($max > 2) {
                    $addonRules = [
                        'participant_id' => [
                            'required',
                            'array',
                            'size:2'
                        ],
                    ];
                    $rules = array_merge($rules, $addonRules);
                }
            }
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
            'participant_id.max' => 'The participants may not have more than :max people.',
            'participant_id.size' => 'The participants can only be :size people.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->session()->flash('type', $this->get('type'));
            $this->session()->flash('implementation', $this->get('implementation'));
        });
    }
}
