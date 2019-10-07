<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendance extends FormRequest
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
            'type' => [
                'required',
            ],
            'destination' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Visitasi';
                }),
            ],
            'participant' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Visitasi';
                }),
                'array',
                'min:1'
            ],
            'participant.*' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Visitasi';
                }),
                'min:1'
            ],
            'transportation' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
            ],
            'participant_id' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
                'array',
                'min:2'
            ],
            'participant_id.*' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
                'min:2'
            ],
            'date' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
            ],
            'arrival_point' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
            ],
            'contact_person' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
            ],
            'until_date' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == 'Audiensi';
                }),
                // 'after:date',
            ],
            'submission_letter' => [
                Rule::requiredIf(function () {
                    if ( ! $this->isMethod('put')) {
                        return $this->get('type') == 'Visitasi';
                    }
                }),
                'file',
                'mimes:pdf,jpg,jpeg,png'
            ],
        ];
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
            'school_id.required' => __('The school field is required.'),
            'participant_id.required' => __('Choose at least 2 participant.'),
            'participant_id.min' => __('Choose at least 2 participants.'),
        ];
    }
}
