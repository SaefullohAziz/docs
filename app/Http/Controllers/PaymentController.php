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
        $this->middleware('auth');
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
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Approved', 'Sent', 'Refunded'])->pluck('name', 'id')->toArray(),
            'subsidyPayments' => Payment::with(['latestPaymentStatus.status'])->has('subsidy')->join('payment_statuses', 'payment_statuses.id', '=', DB::raw('(SELECT id FROM payment_statuses WHERE payment_statuses.payment_id = payments.id ORDER BY id DESC LIMIT 1)'))->join('statuses', 'payment_statuses.status_id', '=', 'statuses.id')->where('statuses.name', 'Published')->select('payments.*')->get(),
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
                    return (date('d-m-Y h:m:s', strtotime($data->created_at)));
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
                ->rawColumns(['DT_RowIndex', 'payment_receipt', 'bank_account_book', 'action'])
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
        $view = [
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
        $request->request->add(['school_id' => auth()->user()->school->id]);
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'total' => str_replace(',', '', $request->total)
        ]);
        $payment = Payment::create($request->all());
        $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request);
        $payment->save();
        return redirect(url()->previous())->with('alert-success', $this->createdMessage);
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
            return redirect()->route('payment.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Payment Confirmation Detail'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Detail')
            ],
            'types' => array_merge($this->types, [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ]),
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'payment' => $payment
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
            return redirect()->route('payment.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Edit Payment Confirmation'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Edit')
            ],
            'types' => array_merge($this->types, [
                'Subsidi' => 'Subsidi', 
                'Commitment Fee' => 'Commitment Fee', 
            ]),
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'payment' => $payment
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
            return redirect()->route('payment.index')->with('alert-danger', $this->noPermission);
        }
        $request->merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'total' => str_replace(',', '', $request->total)
        ]);
        $payment->fill($request->all());
        $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request, $payment->payment_receipt);
        $payment->save();
        return redirect(url()->previous())->with('alert-success', $this->updatedMessage);
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
            return redirect()->route('payment.index')->with('alert-danger', $this->noPermission);
        }
        $view = [
            'title' => __('Fill Payment Confirmation'),
            'breadcrumbs' => [
                route('payment.index') => __('Payment Confirmation'),
                null => __('Edit')
            ],
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
            'payment' => $payment
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
            return redirect()->route('payment.index')->with('alert-danger', $this->noPermission);
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
            $installment = $payment->installment()->create($request->only([
                'date', 'total', 'method', 'payment_receipt', 'bank_sender'
            ]));
            $installment->payment_receipt = $this->uploadPaymentReceipt($payment, $request);
            $installment->save();
        }
        $payment->updated_at = now();
        if ($payment->installment()->count() == 0) {
            $payment->repayment = $request->repayment;
            $payment->invoice = $request->invoice;
            $payment->total = $request->total;
            if ($request->repayment == 'Paid in cash') {
                $payment->date = $request->date;
                $payment->method = $request->method;
                $payment->bank_sender = $request->bank_sender;
                $payment->payment_receipt = $this->uploadPaymentReceipt($payment, $request, $payment->payment_receipt);
            }
        }
        $payment->save();
        return redirect(url()->previous())->with('alert-success', $this->updatedMessage);
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
            $filename = 'payment_receipt_'.date('d_m_y_h_m_s_').md5(uniqid(rand(), true)).'.'.$request->payment_receipt->extension();
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
        return response()->json(['status' => true, 'message' => $this->deletedMessage]);
    }
}
