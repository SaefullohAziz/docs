<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'type' => [
                Rule::requiredIf(function () {
                    if (auth()->guard('admin')->check()) {
                        return $this->isMethod('post');
                    }
                }),
            ],
            'school_id' => [
                Rule::requiredIf(function () {
                    if (auth()->guard('admin')->check()) {
                        if ($this->isMethod('post')) {
                            return $this->get('type') == 'School';
                        }
                    }
                }),
            ],
            'username' => [
                Rule::requiredIf(function () {
                    if (auth()->guard('admin')->check()) {
                        return $this->get('type') == 'Staff';
                    }
                }),
                'unique:staffs,username',
                'unique:users,username'
            ],
            'name' => [
                Rule::requiredIf(function () {
                    if (auth()->guard('admin')->check()) {
                        return  ! $this->is('admin/account/school/*');
                    }
                }),
            ],
            'email' => [
                'required',
                'email',
                'unique:staffs,email',
                'unique:users,email'
            ],
            'password' => [
                Rule::requiredIf(auth()->guard('admin')->check()),
            ],
            'password_confirmation' => [
                Rule::requiredIf(auth()->guard('admin')->check()),
                'same:password'
            ],
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore(auth()->user()),
                    'unique:staffs,email'
                ],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        if (auth()->guard('admin')->check()) {
            if ($this->isMethod('put')) {
                $user = $this->route('user');
                if ($this->is('admin/account/me')) {
                    $user = auth()->guard('admin')->user();
                }
                $addonRules = [
                    'username' => [
                        Rule::requiredIf( ! $this->is('admin/account/school/*')),
                        Rule::unique('staffs')->ignore($user),
                        Rule::unique('users')->ignore($user),
                    ],
                    'email' => [
                        Rule::requiredIf( ! $this->is('admin/account/school/*')),
                        'email',
                        Rule::unique('staffs')->ignore($user),
                        Rule::unique('users')->ignore($user),
                    ],
                    'password' => [],
                    'password_confirmation' => [
                        'same:password'
                    ],
                ];
                $rules = array_merge($rules, $addonRules);
            }
        }
        return $rules;
    }
}
