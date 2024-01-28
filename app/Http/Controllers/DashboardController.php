<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Mail\ScheduleReported;
use App\Mail\ScheduleUpdated;
use App\Models\Category;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = User::find($user->id);

        # member biasa
        switch ($user->role_id) {
            case 1:
                $user = User::find(auth()->user()->id);
                $orders = Order::where('order_status', 'ongoing')->paginate(5)->appends(request()->query());
                $compact = compact('orders', 'user');
                break;
            case 2:
                $scheduleList = $user->charges()->where('date', '>=', Carbon::now()->toDateString())
                    ->orderBy('date')->with('order', 'order.user')->paginate(5)->appends(request()->query());

                $schedules = $user->charges()->where('date', '>=', Carbon::now()->toDateString())
                    ->orderBy('date')->paginate(5)->appends(request()->query());

                $schedule = $schedules->first();

                $reports = $user->charges()->where('date', '<', Carbon::now()->toDateString())
                    ->whereDoesntHave('report')->paginate(5)
                    ->appends(request()->query());

                // dd($scheduleList->count());

                $compact = compact('schedule', 'schedules', 'user', 'reports', 'scheduleList');
                break;
            case 3:
                $compact = compact('user');
                break;
            default:
                $totalBill = 0;
                $orders = $user->orders()->whereIn('order_status', ['pending', 'ongoing'])
                    ->limit(3)->orderByRaw('
                CASE
                    WHEN order_status="ongoing" THEN 1
                    WHEN order_status="pending" THEN 2
                    ELSE 3
                END ASC
                ')->get();

                $schedule = [];
                $report = [];
                if ($orders->count() > 0) {
                    # menghitung total bill dari order dan transaction selain Down Payment
                    $totalBill = $orders->sum(fn($detail) => $detail['total_price']);
                    $transaction = Transaction::whereIn('order_id',$orders->pluck('id')->toArray())
                    ->whereHas('transactionDetails',function($q){
                        $q->where('product','!=','Down Payment');
                    })
                    ->where('status','=','success')->selectRaw('SUM(total) as total')
                    ->first();
                    $totalBill -= $transaction->total;
                    $schedule = $orders->first()->schedules()->orderByDesc('id')->first();
                    $report = $orders->first()->schedules()->orderByDesc('id')->first();
                }

                $compact = compact('user', 'orders', 'schedule','totalBill', 'report');
                break;
        }

        return view('dashboard.index', $compact);
    }

    public function scheduleReportCreate(Schedule $schedule)
    {
        $this->authorize('createReport', $schedule);

        $vendorList = Vendor::all();
        $categories = Category::all();

        return view('dashboard.report.create', compact('schedule', 'vendorList', 'categories'));
    }

    public function scheduleReportStore(Request $request, Schedule $schedule)
    {
        $this->authorize('createReport', $schedule);

        $validation = $request->validate([
            'photo' => 'required|image|mimes:png,jpg|max:2048',
            'note' => 'required|string',
            'next_schedule' => 'nullable',
            'vendors' => 'array|min:1|required',
            'vendors.id.*' => 'required|exists:vendors,id',
            'vendors.total_price.*' => 'required|numeric',
            'vendors.note.*' => 'required|string',
        ]);

        return DB::transaction(function () use ($validation, $schedule) {
            # buat report
            $validation['photo'] = $validation['photo']->store('assets/img', 'public');
            $report = $schedule->report()->create(Arr::only($validation, ['photo', 'note']));

            # buat order Detail

            $vendors = $validation['vendors'];

            $vendors = array_map(function ($id, $total_price, $note) use ($schedule) {
                return [
                    'order_id' => $schedule->order_id,
                    'vendor_id' => $id,
                    'total' => $total_price,
                    'note' => $note,
                ];
            }, $vendors['id'], $vendors['total_price'], $vendors['note']);

            $schedule->orderDetail()->createMany($vendors);

            # update total price pada Order
            $total = array_sum($validation['vendors']['total_price']);
            $schedule->order()->increment('total_price', $total);

            if (isset($validation['next_schedule']) && $validation['next_schedule'] == 'on') {
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
                $schedule->order->schedules()->create([
                    'staff_wo_id' => $user->id,
                    'title' => 'Next Meeting',
                    'date' => $date,
                ]);
            }

            # send email
            Mail::to($schedule->order->user)->queue(new ScheduleReported($schedule));

            $data = [
                'url' => route('report.show', ['report' => $report->id]),
                'message' => "Report Added"
            ];
            return response()->json($data, 201);
        });
    }

    public function scheduleEdit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        return view('dashboard.schedule.edit', compact('schedule'));
    }

    public function scheduleUpdate(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $validation = $request->validate([
            'title' => 'required||string',
            'date' => 'required|date|after_or_equal:' . Carbon::parse($schedule->date)->toDateString(),
            'location' => 'required|string',
            'note' => 'string|nullable',
        ]);

        try {
            $schedule->update($validation);

            Mail::to($schedule->order->user)->queue(new ScheduleUpdated($schedule));

            return redirect('/');
        } catch (Exception $err) {

            return redirect()->back()->withErrors(['message' => $err->getMessage()]);
        }
    }

    public function scheduleDelete(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();
        return redirect()->back();
    }

    public function vendorDetail(Vendor $vendor)
    {
        $vendor = $vendor->loadMissing('category');
        return response()->json($vendor, 200);
    }

    public function vendorStore(StoreVendorRequest $request)
    {
        return Vendor::create($request->validated());
    }
}
