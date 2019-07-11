<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePayment extends FormRequest
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
            'invoice' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('type'))) {
                        return $this->get('type') != 'Biaya Pengiriman Mikrotik';
                    }
                }),
            ],
            'date' => ['required', 'date'],
            'total' => ['required'],
            'method' => ['required'],
            'bank_sender' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('method'))) {
                        return $this->get('method') == 'ATM';
                    }
                }),
            ],
        ];
        if (auth()->guard('admin')->check()) {
            $addonRules = [
                'school_id' => ['required']
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
