<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $orders = $user->orders()->orderByRaw("
        CASE
            WHEN order_status = 'ongoing' THEN 1
            WHEN order_status = 'pending' THEN 2
            ELSE 3
        END,
        updated_at DESC
        ")->with('schedules', 'schedules.report')
            ->with(['orderDetails' => function ($q) {
                $q->orderByDesc('id');
            }])
            ->paginate(5)->appends(request()->query());
        return view('dashboard.order.index', compact('orders', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $afterDate = Carbon::now()->addMonths(3)->toString();
        $validation = $request->validate([
            'plan_date' => "required|date|after:{$afterDate}"
        ]);

        return DB::transaction(function () use ($validation) {
            $defaultDP = 5000000;
            $user = auth()->user();
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $order = $user->orders()->create([
                'plan_date' => $validation['plan_date'],
                'price' => $defaultDP
            ]);

            # buat id transaksi nya
            $transaction = $order->transactions()->create();
            # buat detail transaksi nya karena mungkin dapat memuat banyak barang
            $transaction->transactionDetails()->create([
                'product' => 'Down Payment Weeding Organizer',
                'sub_total' => $defaultDP
            ]);

            return response()->json([
                'message' => "Order Created",
                'url_redirect' => route('transaction.show', ['transaction' => $transaction->id])
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = $order->loadMissing(
            'transactions',
            'installments',
            'installments.transaction',
            'orderDetails'
        );
        $transactions = $order->transactions;
        // dd($order->schedules);
        // $transactions = $transaction->merge($insta)
        // dd($transactions);
        return view('dashboard.order.show', compact('order', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function scheduleCreate(Order $order)
    {
        $user = User::find(auth()->user()->id);

        # cek tanggal untuk buat tanggal yang tidak bentrok
        $date = Carbon::now()->addDays(7)->toDateString();
        $scheduleDate = Schedule::where([
            ['date', '=', $date],
            ['staff_wo_id', '=', $user->id],
        ])->first();
        
        while ($scheduleDate) {
            $date = Carbon::parse($date)->addDay()->toDateString();
            $scheduleDate = Schedule::where([
                ['date', '=', $date],
                ['staff_wo_id', '=', $user->id],
                ])->first();
        }
            

        # create first schedule
        $order->schedules()->create([
            'staff_wo_id' => $user->id,
            'title' => 'Next Meeting',
            'date' => $date,
        ]);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
