<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamReadiness extends FormRequest
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
            'exam_type' => [
                'required',
            ],
            'sub_exam_type' => [
                Rule::requiredIf(function () {
                    $subExam = \App\ExamType::when( ! empty($this->get('exam_type')), function ($query) {
                        $query->where('name', $this->get('exam_type'));
                    })
                    ->where('sub_name', '!=', '')
                    ->whereNotNull('sub_name')
                    ->pluck('sub_name', 'id')
                    ->toArray();
                    return count($subExam) > 0;
                }),
                function ($attribute, $value, $fail) {
                    if ($this->get('exam_type') == 'Axioo' || $this->get('exam_type') == 'Remidial Axioo') {
                        if (count($this->get('sub_exam_type')) == 0) {
                            $fail('Choose at least one ' . $attribute);
                        }
                    }
                },
            ],
            'sub_exam_type.*' => [
                Rule::requiredIf(is_array($this->get('sub_exam_type'))),
                'min:1'
            ],
            'ma_status' => [
                Rule::requiredIf($this->get('exam_type') == 'MTCNA'),
            ],
            'execution' => [
                Rule::requiredIf($this->get('ma_status') == 'Belum'),
            ],
            'reference_school' => [
                Rule::requiredIf($this->get('execution') == 'Bergabung'),
            ],
            'confirmation_of_readiness' => [
                Rule::requiredIf($this->get('execution') == 'Bergabung'),
            ],
            'student_id' => [
                'required',
                'array',
                'min:1'
            ],
            'student_id.*' => [
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
