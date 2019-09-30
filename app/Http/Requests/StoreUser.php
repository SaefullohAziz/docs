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
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'unique:staffs,email'
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
            $addonRules = [
                'username' => [
                    'required',
                    'unique:staffs,username',
                    'unique:users,username'
                ],
                'name' => [
                    'required',
                ],
                'email' => [
                    'required',
                    'email',
                    'unique:staffs,email',
                    'unique:users,email'
                ],
                'password' => [
                    'required',
                ],
                'password_confirmation' => [
                    'required',
                    'same:password'
                ],
            ];
            $rules = array_merge($rules, $addonRules);
            if ($this->isMethod('put')) {
                $user = $this->route('user');
                if ($this->is('admin/account/me')) {
                    $user = auth()->guard('admin')->user();
                }
                $addonRules = [
                    'username' => [
                        'required',
                        Rule::unique('staffs')->ignore($user),
                        'unique:users,username'
                    ],
                    'email' => [
                        'required',
                        'email',
                        Rule::unique('staffs')->ignore($user),
                        'unique:users,email'
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
