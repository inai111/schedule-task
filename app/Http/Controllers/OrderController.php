<?php

namespace App\Http\Controllers;

use App\Mail\NotificationTransaction;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        switch ($user->role_id) {
            case 1:
                $orders = Order::orderByRaw("
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
                break;
            case 2:
                $orders = Order::whereIn('order_status', ['ongoing', 'pending'])
                    ->whereHas('schedules', function ($qq) use ($user) {
                        $qq->where('staff_wo_id', $user->id);
                    })
                    ->orderByRaw("
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
                break;
            default:
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
                break;
        }
        return view('dashboard.order.index', compact('orders', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create',Order::class);
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
            $transaction = $order->transactions()->create(
                ['total'=>$defaultDP]
            );
            # buat detail transaksi nya karena mungkin dapat memuat banyak barang
            $planDate = date("F, d-m-Y",strtotime($validation['plan_date']));
            $transaction->transactionDetails()->create([
                'product' => 'Down Payment',
                'description' => "Event Date Plan : {$planDate}",
                'price' => $defaultDP
            ]);

            Mail::to($user->email)->queue(new NotificationTransaction($transaction));

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
            'orderDetails'
        );
        $transactions = $order->transactions;

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
        $this->authorize('createSchedule', $order);

        $user = User::find(auth()->user()->id);

        # cek tanggal untuk buat tanggal yang tidak bentrok
        $date = Carbon::now()->addDays(7)->toDateString();
        $scheduleDate = Schedule::where([
            ['date', '=', $date],
            ['staff_wo_id', '=', $user->id],
        ])->first();

        $plan_date = Carbon::parse($order->plan_date)->toDateString();
        if ($date == $plan_date || $date > $plan_date) {
            $date = Carbon::now()->addDay()->toDateString();
            if ($date == $plan_date || $date > $plan_date) {
                return redirect()->back()->withErrors('message','No Date Left');
            }
        }

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
