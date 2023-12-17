<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction = $transaction->loadMissing('transactionable', 'transaction_details');
        $transactionable = $transaction->transactionable;

        # cek apakah $order berasal dari model Order
        if ($transactionable instanceof \App\Models\Order) {
            $user = $transactionable->user;
            $order = $transactionable;
        } elseif ($transactionable instanceof \App\Models\Installment) {
            $order = $transactionable->order;
            $user = $order->user;
        }

        return view('dashboard.transaction.show', compact('transaction', 'order', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function snap(Transaction $transaction)
    {
        $this->authorize('update',$transaction);

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $snapToken = $transaction->snap_token;

        # jika ada token tersimpan di database, cek apakah status sudah berhasil
        if (!empty($snapToken)) {
            /**
             * @var Object
             */
            $cek = \Midtrans\Transaction::status($transaction->id);

            # kalau sudah terbayar maka update data transaction
            if ($cek->transaction_status === 'settlement') {
                return $this->transactionpaid($transaction);
            }
        }

        # cek apakah sudah expired tokennya
        $lastUpdate = Carbon::parse($transaction->updated_at)->addHours(2);
        if ($transaction->status === 'waiting' && (empty($transaction->snap_token) || $lastUpdate->isPast())) {

            $itemDetails = $transaction->transaction_details->map(function ($row) {
                return [
                    "id" => $row->id,
                    "price" => $row->sub_total,
                    "quantity" => $row->qty,
                    "name" => $row->product,
                    "merchant_name" => "Wedding Organizer"
                ];
            });

            $params = [
                "transaction_details" => [
                    "order_id" => $transaction->id,
                    "gross_amount" => $transaction->transaction_details->sum(fn ($detail) => $detail['sub_total'])
                ],
                "item_details" => $itemDetails,
                "customer_details" => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone_number,
                ],
                "enabled_payments" => ["bca_klikpay"]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();
        }

        return response()->json(['token' => $snapToken], 200);
    }

    private function transactionpaid($transaction)
    {
        $transaction->status = 'success';
        $transaction->save();

        $order = $transaction->transactionable;

        # barangkali $order merupakan isi model angsuran
        if ($order instanceof \App\Models\Installment) {
            # kalau sudah sesuai angsuran maka ubah status order ke success
            $order = $order->order;
        }else{
            # jika masih waiting, maka dapat di pastikan jika ini sedang bayar DP
            if($order->status === 'waiting'){
                $order->status = 'ongoing';
                $order->save();

                # buat schedule
            }

        }

        # kirim status no content untuk reload page
        return response('',204);
    }
}
