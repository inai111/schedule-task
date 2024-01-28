<?php

namespace App\Http\Controllers;

use App\Livewire\TransactionTable;
use App\Mail\TransactionPaid;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $transactions = Transaction::
        // orderByRaw("
        // CASE
        //     WHEN status = 'success' THEN 0
        //     else 1
        // END DESC
        // ")->get();
        return view('dashboard.transaction.index');
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
        return TransactionTable::class;
        $transaction = $transaction->loadMissing('order', 'transactionDetails');

        $order = $transaction->order;

        $this->cekStatus($transaction);

        # cek apakah $order berasal dari model Order
        $user = $order->user;

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

        $snapToken = $transaction->snapToken;

        if ($transaction->status === 'waiting') {
            try {
                /**
                 * @var Object
                 */
                $cek = \Midtrans\Transaction::status($transaction->slug);

                # kalau sudah terbayar maka update data transaction
                if ($cek->transaction_status === 'settlement') {
                    return $this->transactionpaid($transaction);
                }
            } catch (Exception $e) {
                $code = $e->getCode();

                if ($code === 404) {
                    $snapToken = $this->midtransSnapToken($transaction);
                }
            }

            return response()->json(['token' => $snapToken], 200);
        }
        
        return response('', 204);
    }

    private function midtransSnapToken($transaction)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        $itemDetails = $transaction->transactionDetails->map(function ($row) {
            return [
                "id" => $row->id,
                "price" => $row->price,
                "quantity" => $row->qty,
                "name" => $row->product,
                "merchant_name" => "Wedding Organizer"
            ];
        });

        $params = [
            "transaction_details" => [
                "order_id" => $transaction->slug,
                "gross_amount" => $transaction->total
            ],
            "item_details" => $itemDetails,
            "customer_details" => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone_number,
            ],
            "enabled_payments" => ["bca_klikpay"]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->exp_date = Carbon::now()->addDay()->format('Y-m-d 00:00:00');
            $transaction->snap_token = $snapToken;
        } catch (Exception $e) {
            if ((int)$e->getCode() === 400) {
                $transaction->slug = Uuid::uuid1();
            }
        }
        $transaction->save();

        return $snapToken;
    }

    public function transactionpaid($transaction)
    {
        try{
            return DB::transaction(function () use ($transaction) {
                $user = auth()->user();

                $transaction->status = 'success';
                $transaction->method = 'transfer';
                $transaction->save();
    
                $order = $transaction->order;
    
                if ($order->order_status === 'pending') {
                    $order->order_status = 'ongoing';
                    $order->save();
    
                    # buat schedule pertama kali
    
                    # cek tanggal untuk buat tanggal yang tidak bentrok
                    $date = Carbon::now()->addDays(7)->toDateString();
                    $staffLapangan = User::where('role_id', 2)->whereDoesntHave('charges', function ($query) use ($date) {
                        $query->where('date', $date);
                    })->get();
    
                    while ($staffLapangan->count() == 0) {
                        $date = Carbon::parse($date)->addDay()->toDateString();
                        $staffLapangan = User::where('role_id', 2)
                        ->whereDoesntHave('charges', function ($query) use ($date) {
                            $query->where('date', $date);
                        })->get();
                    }
    
    
                    # create first schedule
                    if ($order->schedules->count() == 0) {
                        $order->schedules()->create([
                            'staff_wo_id' => $staffLapangan->first()->id,
                            'title' => 'First Meeting',
                            'date' => $date,
                        ]);
                    }
                }

                Mail::to($user->email)->queue(new TransactionPaid());

                # kirim status no content untuk reload page
                return response('', 204);
            });
        }catch(Exception $e){
            return response()->json($e->getMessage(), 400);
        }

    }

    private function cekStatus($transaction)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        // $snapToken = $transaction->snap_token;

        # jika ada token tersimpan di database, cek apakah status sudah berhasil
        if ($transaction->status == 'waiting') {
            try {
                /**
                 * @var Object
                 */
                $cek = \Midtrans\Transaction::status($transaction->slug);

                # kalau sudah terbayar maka update data transaction
                if ($cek->transaction_status === 'settlement') {
                    return $this->transactionpaid($transaction);
                }
            } catch (Exception $e) {
                $transaction->snap_token = '';
                $transaction->save();
            }
        }
    }
}
