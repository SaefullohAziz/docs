<?php

namespace App\Http\Controllers;

use Auth;
use App\Payment;
use App\School;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayment;
use App\Http\Requests\ConfirmPayment;
use DataTables;
use Validator;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $table;
    private $types;
    private $methods;
    private $bankSenders;
    private $banks;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'payments';
        $this->types = [
            // 'Subsidi' => 'Subsidi', 
            'Axioo Smart School Pack' => 'Axioo Smart School Pack', 
            'Axioo Smart Factory Support' => 'Axioo Smart Factory Support', 
            // 'Commitment Fee' => 'Commitment Fee', 
            'Advance Training' => 'Advance Training', 
            'Biaya Pengiriman Mikrotik' => 'Biaya Pengiriman Mikrotik', 
            'Pembelian Produk' => 'Pembelian Produk', 
            'Lainnya (Ongkir, dsb)' => 'Lainnya (Ongkir, dsb)',
        ];
        $this->methods = [
            'Setor Tunai' => 'Setor Tunai',
            'ATM' => 'ATM',
            'SMS Banking' => 'SMS Banking',
            'Internet Banking' => 'Internet Banking'
        ];
        $this->bankSenders = [
            'Mandiri' => 'Mandiri',
            'BCA' => 'BCA',
            'BRI' => 'BRI',
            'Lain-Lain' => 'Lain-Lain'
        ];
        $this->banks = [
            'BCA' => 'BCA', 
            'MANDIRI' => 'MANDIRI', 
            'BNI' => 'BNI', 
            'BRI' => 'BRI',
            'lain' => 'Lainnya',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Payment'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment'),
                null => __('Data')
            ],
            'types' => array_merge($this->types, [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ]),
            'statuses' => Status::byNames(['Created', 'Processed', 'Approved', 'Sent', 'Refunded'])->pluck('name', 'id')->toArray(),
            'subsidyPayments' => Payment::with(['paymentStatus.status'])->has('subsidy')->join('payment_statuses', 'payment_statuses.id', '=', DB::raw('(SELECT id FROM payment_statuses WHERE payment_statuses.payment_id = payments.id ORDER BY created_at DESC LIMIT 1)'))->join('statuses', 'payment_statuses.status_id', '=', 'statuses.id')->whereHas('school', function ($school) {
                $school->where('id', auth()->user()->school->id);
            })->where('statuses.name', 'Published')->select('payments.*')->get(),
            'trainingPayments' => Payment::with(['paymentStatus.status'])->has('training')->join('payment_statuses', 'payment_statuses.id', '=', DB::raw('(SELECT id FROM payment_statuses WHERE payment_statuses.payment_id = payments.id ORDER BY created_at DESC LIMIT 1)'))->join('statuses', 'payment_statuses.status_id', '=', 'statuses.id')->whereHas('school', function ($school) {
                $school->where('id', auth()->user()->school->id);
            })->where('statuses.name', 'Published')->select('payments.*')->get(),
        ];
        return view('payment.index', $view);
    }

    /**
     * Show a listing of the resource for datatable.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::list($request);
            return DataTables::of($payments)
                ->addColumn('DT_RowIndex', function ($data)
                {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('type', function($data) {
                    return $data->type . ' ' . (empty($data->subsidy_type)?'':'('.link_to(route('admin.subsidy.show', $data->subsidy_id), $data->subsidy_type, ['target' => '_blank']).')') . (empty($data->training_type)?'':'('.link_to(route('admin.training.show', $data->training_id), $data->training_type, ['target' => '_blank']).')');
                })
                ->editColumn('payment_receipt', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('payment/payment-receipt'), 'file' => encrypt($data->payment_receipt)]).'" class="btn btn-sm btn-success '.( ! isset($data->payment_receipt)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('bank_account_book', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('payment/bank-account-book'), 'file' => encrypt($data->bank_account_book)]).'" class="btn btn-sm btn-success '.( ! isset($data->bank_account_book)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('payment.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('payment.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
                })
                ->rawColumns(['DT_RowIndex', 'type', 'payment_receipt', 'bank_account_book', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->cant('create', Payment::class)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'back' => route('payment.index'),
            'title' => __('Create Payment Confirmation'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Create')
            ],
            'types' => $this->types,
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks
        ];
        return view('payment.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePayment $request)
    {
        if (auth()->user()->cant('create', Payment::class)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $request->request->add(['school_id' => auth()->user()->school->id]);
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'total' => str_replace(',', '', $request->total)
        ]);
        $payment = Payment::create($request->all());
        $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request);
        $payment->save();
        return redirect(url()->previous())->with('alert-success', __($this->createdMessage));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        if (auth()->user()->cant('view', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('payment.index'),
            'title' => __('Payment Confirmation Detail'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Detail')
            ],
            'subtitle' => $payment->subsidy->count()?__('Subsidy').' '.$payment->subsidy[0]->type:($payment->training->count()?__('Training').' '.$payment->training[0]->type:null),
            'types' => array_merge($this->types, [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ]),
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'data' => $payment
        ];
        return view('payment.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        if (auth()->user()->cant('update', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('payment.index'),
            'title' => __('Edit Payment Confirmation'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Edit')
            ],
            'subtitle' => $payment->subsidy->count()?__('Subsidy').' '.$payment->subsidy[0]->type:($payment->training->count()?__('Training').' '.$payment->training[0]->type:null),
            'types' => array_merge($this->types, [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ]),
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'data' => $payment
        ];
        return view('payment.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(StorePayment $request, Payment $payment)
    {
        if (auth()->user()->cant('update', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission));
        }
        if ($request->filled('type')) {
            $request->merge(['type' => $payment->type]);
        }
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'total' => str_replace(',', '', $request->total)
        ]);
        $payment->fill($request->all());
        $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request, $payment->payment_receipt);
        $payment->save();
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Show the form for confirming the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function fill(Payment $payment)
    {
        if (auth()->user()->cant('confirm', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->user()->cant('commitmentFeeCheck', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission) . ' ' . __('Payment confirmation past due.'));
        }
        $view = [
            'back' => route('payment.index'),
            'title' => __('Fill Payment Confirmation'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Edit')
            ],
            'subtitle' => $payment->subsidy->count()?__('Subsidy').' '.$payment->subsidy[0]->type:__('Training').' '.$payment->training[0]->type,
            'types' => [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ],
            'repayments' => [
                'Paid in cash' => __('Paid in cash'),
                'Paid in installment' => __('Paid in installment')
            ],
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'data' => $payment
        ];
        return view('payment.fill', $view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function confirm(ConfirmPayment $request, Payment $payment)
    {
        if (auth()->user()->cant('confirm', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->user()->cant('commitmentFeeCheck', $payment)) {
            return redirect()->route('payment.index')->with('alert-danger', __($this->noPermission) . ' ' . __('Payment confirmation past due.'));
        }
        if ($payment->training()->count()) {
            $request->merge(['repayment' => 'Paid in cash']);
        }
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'total' => str_replace(',', '', $request->total),
            'installment_ammount' => str_replace(',', '', $request->installment_ammount)
        ]);
        if ($request->repayment == 'Paid in installment' || $payment->repayment == 'Paid in installment') {
            $request->merge([
                'total' => $request->installment_ammount
            ]);
            $installment = $payment->installments()->create($request->only([
                'date', 'total', 'method', 'payment_receipt', 'bank_sender'
            ]));
            $installment->payment_receipt = $this->uploadPaymentReceipt($payment, $request);
            $installment->save();
        }
        $payment->updated_at = now();
        if ($payment->installments()->count() == 0) {
            $payment->repayment = $request->repayment;
            $payment->invoice = $request->invoice;
            $payment->total = (empty($request->total)?$payment->total:$request->total);
            if ($payment->subsidy->count()) {
                if ($payment->subsidy[0]->type == 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)') {
                    $payment->npwp_number = $request->npwp_number;
                    $payment->npwp_on_behalf_of = $request->npwp_on_behalf_of;
                    $payment->npwp_address = $request->npwp_address;
                    $payment->npwp_file = $this->uploadNpwpFile($payment, $request, $payment->npwp_file);
                }
            } elseif ($payment->training->count()) {
                $payment->receiver_bank_name = $request->receiver_bank_name;
                $payment->receiver_bill_number = $request->receiver_bill_number;
                $payment->receiver_on_behalf_of = $request->receiver_on_behalf_of;
                $payment->bank_account_book = $this->uploadBankAccountBook($payment, $request, $payment->bank_account_book);
            }
            if ($request->repayment == 'Paid in cash') {
                $payment->date = $request->date;
                $payment->method = $request->method;
                $payment->bank_sender = $request->bank_sender;
                $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request, $payment->payment_receipt);
            }
        }
        $payment->save();
        return redirect()->route('payment.index')->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Upload payment letter
     * 
     * @param  \App\Payment  $payment
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadNpwpFile($payment, Request $request, $oldFile = null)
    {
        if ($request->hasFile('npwp_file')) {
            $filename = 'npwp_file_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->npwp_file->extension();
            $path = $request->npwp_file->storeAs('public/payment/npwp/'.$payment->id, $filename);
            return $payment->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Upload payment letter
     * 
     * @param  \App\Payment  $payment
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadCommitmentLetter($payment, Request $request, $oldFile = null)
    {
        if ($request->hasFile('commitment_letter')) {
            $filename = 'commitment_letter_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->commitment_letter->extension();
            $path = $request->commitment_letter->storeAs('public/payment/commitment-letter/'.$payment->id, $filename);
            return $payment->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Upload payment letter
     * 
     * @param  \App\Payment  $payment
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadBankAccountBook($payment, Request $request, $oldFile = null)
    {
        if ($request->hasFile('bank_account_book')) {
            $filename = 'bank_account_book_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->bank_account_book->extension();
            $path = $request->bank_account_book->storeAs('public/payment/bank-account-book/'.$payment->id, $filename);
            return $payment->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Upload payment letter
     * 
     * @param  \App\Payment  $payment
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPaymentReceipt($payment, Request $request, $oldFile = null)
    {
        if ($request->hasFile('payment_receipt')) {
            $filename = 'payment_receipt_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->payment_receipt->extension();
            $path = $request->payment_receipt->storeAs('public/payment/payment-receipt/'.$payment->id, $filename);
            return $payment->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Export payment listing as Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        return (new PaymentsExport($request))->download('payment-'.date('d-m-Y-h-m-s').'.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Payment::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
