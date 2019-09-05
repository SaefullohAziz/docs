<?php

namespace App\Http\Requests;

use App\School;
use App\SchoolPhoto;
use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolPhoto extends FormRequest
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
            $school = $this->route('school');
        } else {
            $school = School::find(auth()->user()->school->id);
        }
        $photoCount = SchoolPhoto::where('school_id', $school->id)->where('category', $this->input('category'))->count();
        $rules = [
            'category' => ['required'],
            'photos' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($photoCount) {
                    if (count($value) + $photoCount > 10) {
                        $fail('Only 10 photos for one category. Please delete some photos before uploading again.');
                    }
                }
            ],
            'photos.*' => [
                'required',
                'min:1',
                'mimes:png,jpg,jpeg,gif'
            ],
        ];
        return $rules;
    }
}
