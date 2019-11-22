<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Payment;
use App\School;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\PaymentProcessed;
use App\Events\PaymentApproved;
use App\Events\PaymentSent;
use App\Events\PaymentRefunded;
use App\Http\Requests\StorePayment;
use DataTables;
use Validator;
use App\Exports\PaymentsExport;

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
        if ( ! auth()->guard('admin')->user()->can('access ' . $this->table)) {
            return redirect()->route('admin.home')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Payment Confirmation'),
            'breadcrumbs' => [
                route('admin.payment.index') => __('Payment Confirmation'),
                null => __('Data')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'statuses' => Status::byNames(['Created', 'Processed', 'Approved', 'Sent', 'Refunded'])->pluck('name', 'id')->toArray(),
            'kolis' => collect(range(1, 100))->combine(range(1, 100))->map(function ($number) {
                return $number . ' koli';
            })->toArray(),
            'expeditions' => [
                'Wali Pitue' => 'Wali Pitue',
                'JN Cargo' => 'JN Cargo'
            ],
        ];
        return view('admin.payment.index', $view);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bin()
    {
        if ( ! auth()->guard('admin')->user()->can('bin ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'back' => route('admin.payment.index'),
            'title' => __('Deleted Payment Confirmation'),
            'breadcrumbs' => [
                route('admin.payment.index') => __('Payment Confirmation'),
                null => __('Deleted')
            ],
        ];
        return view('admin.payment.bin', $view);
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
                ->addColumn('DT_RowIndex', function ($data) {
                    return '<div class="checkbox icheck"><label><input type="checkbox" name="selectedData[]" value="'.$data->id.'"></label></div>';
                })
                ->editColumn('created_at', function($data) {
                    return (date('d-m-Y H:i:s', strtotime($data->created_at)));
                })
                ->editColumn('payment_receipt', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('payment/payment-receipt'), 'file' => encrypt($data->payment_receipt)]).'" class="btn btn-sm btn-success '.( ! isset($data->payment_receipt)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('bank_account_book', function($data) {
                    return '<a href="'.route('download', ['dir' => encrypt('payment/bank-account-book'), 'file' => encrypt($data->bank_account_book)]).'" class="btn btn-sm btn-success '.( ! isset($data->bank_account_book)?'disabled':'').'" title="'.__('Download').'" target="_blank"><i class="fa fa-file"></i>  '.__('Download').'</a>';
                })
                ->editColumn('status', function($data) {
                    return $data->status.' by '.$data->status_by;
                })
                ->addColumn('action', function($data) {
                    return '<a class="btn btn-sm btn-success" href="'.route('admin.payment.show', $data->id).'" title="'.__("See detail").'"><i class="fa fa-eye"></i> '.__("See").'</a> <a class="btn btn-sm btn-warning" href="'.route('admin.payment.edit', $data->id).'" title="'.__("Edit").'"><i class="fa fa-edit"></i> '.__("Edit").'</a>';
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
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Payment::class)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
        $view = [
            'title' => __('Create Payment Confirmation'),
            'breadcrumbs' => [
                route('admin.payment.index') => __('Payment Confirmation'),
                null => __('Create')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks
        ];
        return view('admin.payment.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePayment $request)
    {
        if ( ! auth()->guard('admin')->user()->can('create ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
        }
        if (auth()->guard('admin')->user()->cant('adminCreate', Payment::class)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->unauthorizedMessage));
        }
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
        if ( ! auth()->guard('admin')->user()->can('read ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Payment Confirmation Detail'),
            'breadcrumbs' => [
                route('admin.payment.index') => __('Payment Confirmation'),
                null => __('Detail')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'data' => $payment
        ];
        return view('admin.payment.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
        }
        $view = [
            'title' => __('Edit Payment Confirmation'),
            'breadcrumbs' => [
                route('admin.payment.index') => __('Payment Confirmation'),
                null => __('Edit')
            ],
            'schools' => School::pluck('name', 'id')->toArray(),
            'types' => $this->types,
            'methods' => $this->methods,
            'bankSenders' => $this->bankSenders,
            'banks' => $this->banks,
            'data' => $payment
        ];
        return view('admin.payment.edit', $view);
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
        if ( ! auth()->guard('admin')->user()->can('update ' . $this->table)) {
            return redirect()->route('admin.payment.index')->with('alert-danger', __($this->noPermission));
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
     * Process data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function process(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new PaymentProcessed($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Approve data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function approve(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new PaymentApproved($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

     /**
     * Send data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function send(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new PaymentSent($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Refund data
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function refund(Request $request)
    {
        if ($request->ajax()) {
            if ( ! auth()->guard('admin')->user()->can('approval ' . $this->table)) {
                return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
            }
            event(new PaymentRefunded($request));
            return response()->json(['status' => true, 'message' => __($this->updatedMessage)]);
        }
    }

    /**
     * Export listing as Excel
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
        if ( ! auth()->guard('admin')->user()->can('delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Payment::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('restore ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Payment::onlyTrashed()->whereIn('id', $request->selectedData)->restore();
        return response()->json(['status' => true, 'message' => __($this->restoredMessage)]);
    }

    /**
     * Remove permanently the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPermanently(Request $request)
    {
        if ( ! auth()->guard('admin')->user()->can('force_delete ' . $this->table)) {
            return response()->json(['status' => false, 'message' => __($this->noPermission)], 422);
        }
        Payment::onlyTrashed()->whereIn('id', $request->selectedData)->forceDelete();
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
