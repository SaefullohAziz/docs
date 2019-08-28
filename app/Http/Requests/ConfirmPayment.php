<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmPayment extends FormRequest
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
        $payment = $this->route('payment');
        $remainingPayment = $payment->total-$payment->installment->sum('total');
        $rules = [
            'repayment' => [
                Rule::requiredIf(function () use ($payment) {
                    return $payment->installment()->count() == 0;
                }),
            ],
            'invoice' => [
                Rule::requiredIf(function () use ($payment) {
                    return $payment->installment()->count() == 0;
                }),
            ],
            'date' => ['required', 'date'],
            'total' => [
                Rule::requiredIf(function () use ($payment) {
                    return $payment->installment()->count() == 0;
                }),
            ],
            'method' => ['required'],
            'bank_sender' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('method'))) {
                        return $this->get('method') == 'ATM';
                    }
                }),
            ],
            'installment_ammount' => [
                Rule::requiredIf(function () {
                    if ( ! empty($this->get('repayment'))) {
                        return $this->get('repayment') == 'Paid in installment';
                    }
                }),
                function ($attribute, $value, $fail) use ($remainingPayment) {
                    if ($value > $remainingPayment) {
                        $fail($attribute.' is invalid.');
                    }
                }
            ],
        ];
        return $rules;
    }
}
