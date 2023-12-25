<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction = $transaction->loadMissing('transactionable', 'transactionDetails');
        $transactionable = $transaction->transactionable;

        $this->cekStatus($transaction);

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
    public function update(Request $request, Transaction $transaction)
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
        $this->authorize('update', $transaction);

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        # cek apakah sudah expired tokennya
        $lastUpdate = Carbon::parse($transaction->exp_date);
        if ($transaction->status === 'waiting' && (empty($transaction->snap_token) || $lastUpdate->isPast())) {

            $itemDetails = $transaction->transactionDetails->map(function ($row) {
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
                    "gross_amount" => $transaction->transactionDetails->sum(fn ($detail) => $detail['sub_total'])
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
            $transaction->exp_date = Carbon::now()->addDay()->format('Y-m-d 00:00:00');
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
        }


        if ($order->order_status === 'pending') {
            $order->order_status = 'ongoing';
            // $order->save();

            # buat schedule pertama kali

            # cek tanggal untuk buat tanggal yang tidak bentrok
            $date = Carbon::now()->addDays(7)->toDateString();
            $staffLapangan = User::where('role_id', 2)->whereDoesntHave('charge', function ($query) use ($date) {
                $query->where('date', $date);
            })->get();
            
            while ($staffLapangan->count()==0) {
                $date = Carbon::parse($date)->addDay()->toDateString();
                $staffLapangan = User::where('role_id', 2)->whereDoesntHave('charge', function ($query) use ($date) {
                    $query->where('date', $date);
                })->get();
            }

            # create first schedule
            if($order->schedules->count()==0){
                $order->schedules()->create([
                    'staff_wo_id'=>$staffLapangan->first()->id,
                    'title'=>'First Meeting',
                    'date'=>$date,
                    'status'=>'active',
                ]);
            }

        }

        # kirim status no content untuk reload page
        return response('', 204);
    }

    private function cekStatus($transaction)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        $snapToken = $transaction->snap_token;

        # jika ada token tersimpan di database, cek apakah status sudah berhasil
        if (!empty($snapToken)) {
            try {
                /**
                 * @var Object
                 */
                $cek = \Midtrans\Transaction::status($transaction->id);

                # kalau sudah terbayar maka update data transaction
                if ($cek->transaction_status === 'settlement') {
                    return $this->transactionpaid($transaction);
                }
            } catch (Exception $e) {
                $transaction->snap_token = '';
                // $transaction->save();
            }
        }
    }
}
