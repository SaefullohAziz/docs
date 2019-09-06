<?php

namespace App\Http\Requests;

use App\School;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentClass extends FormRequest
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
        if (auth()->guard('admin')->check()) {
            $school = School::find($this->input('school_id'));
        } else {
            $school = School::find(auth()->user()->school->id);
        }
        $rules = [
            'school_id' => [
                Rule::requiredIf(function () {
                    return auth()->guard('admin')->check();
                }),
            ],
            'department_id' => [
                Rule::requiredIf(function () use ($school) {
                    return $school->implementation->count() > 1;
                }),
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
            'department_id.required' => __('The department_id field is required.'),
        ];
    }
}
