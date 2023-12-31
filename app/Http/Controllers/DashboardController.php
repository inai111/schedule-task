<?php

namespace App\Http\Controllers;

use App\Mail\ScheduleReported;
use App\Mail\ScheduleUpdated;
use App\Models\Order;
use App\Models\Schedule;
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
                $orders = Order::where('order_status','ongoing')->paginate(5)->appends(request()->query());
                $compact = compact('orders','user');
            break;
            case 2:
                $scheduleList = $user->charges()->where('date','>=',Carbon::now()->toDateString())
                ->orderBy('date')->with('order','order.user')->paginate(5)->appends(request()->query());

                $schedules = $user->charges()->where('date','>=',Carbon::now()->toDateString())
                ->orderBy('date')->paginate(5)->appends(request()->query());

                $schedule = $schedules->first();

                $reports = $user->charges()->where('date','<',Carbon::now()->toDateString())
                ->whereDoesntHave('report')->paginate(5)
                ->appends(request()->query());
                
                // dd($scheduleList->count());

                $compact = compact('schedule','schedules','user','reports','scheduleList');
            break;
            default:
                $orders = $user->orders()->whereIn('order_status', ['pending', 'ongoing'])
                    ->limit(3)->orderByRaw('
                CASE
                    WHEN order_status="ongoing" THEN 1
                    WHEN order_status="pending" THEN 2
                    ELSE 3
                END ASC
                ')->get();

                $installments = [];
                $schedule = [];
                $report = [];
                if($orders->count() > 0) {
                    $installments = $orders->first()->installments()->whereHas('transaction', function ($query) {
                        $query->where('status', 'waiting');
                    });
                    $schedule = $orders->first()->schedules()->orderByDesc('id')->first();
                    $report = $orders->first()->schedules()->orderByDesc('id')->first();
                }

                $compact = compact('user', 'orders', 'installments', 'schedule', 'report');
            break;
        }

        return view('dashboard.index', $compact);
    }

    public function scheduleReportCreate(Schedule $schedule)
    {
        $this->authorize('createReport',$schedule);

        $vendorList = Vendor::all();

        return view('dashboard.report.create', compact('schedule','vendorList'));
    }

    public function scheduleReportStore(Request $request, Schedule $schedule)
    {
        $this->authorize('createReport',$schedule);

        $validation = $request->validate([
            'photo'=>'required|image|mimes:png,jpg|max:2048',
            'note'=>'string|required',
            'next_schedule'=>'nullable',
            'vendor.id'=>'nullable|exists:vendors,id',
            'vendor.name'=>'required|string',
            'vendor.address'=>'required|string',
            'vendor.category'=>'required|string',
            'vendor.phone_number'=>'required|string',
            'vendor.bank_name'=>'required|string',
            'vendor.bank_account_name'=>'required|string',
            'vendor.bank_account_number'=>'required|string',
            'vendor.total_price'=>'required|string',
            'vendor.note'=>'nullable|string',
        ]);

        DB::transaction(function()use($validation,$schedule){
            # buat vendor baru
            if($validation['vendor']['id']==null){
                $vendor = Vendor::create(Arr::except($validation['vendor'],['total_price','note']));
            }else{
                $vendor = Vendor::find($validation['vendor']['id']);
            }

            # buat report
            $validation['photo'] = $validation['photo']->store('assets/img','public');
            $schedule->report()->create(Arr::only($validation,['photo','note']));

            # buat order Detail
            $schedule->orderDetail()->create([
                'order_id' => $schedule->order_id,
                'vendor_id' => $vendor->id,
                'total_price' => $validation['vendor']['total_price'],
                'note' => $validation['vendor']['note'],
            ]);

            # update total price pada Order
            $schedule->order()->increment('total_price',$validation['vendor']['total_price']);
            
            if(isset($validation['next_schedule'])&& $validation['next_schedule']=='on'){
                $user = User::find(auth()->user()->id);

                # cek tanggal untuk buat tanggal yang tidak bentrok
                $date = Carbon::now()->addDays(7)->toDateString();
                $scheduleDate = Schedule::where([
                    ['date','=', $date],
                    ['staff_wo_id','=', $user->id],
                ])->first();

                while ($scheduleDate) {
                    $date = Carbon::parse($date)->addDay()->toDateString();
                    $scheduleDate = Schedule::where([
                        ['date','=', $date],
                        ['staff_wo_id','=', $user->id],
                    ])->first();
                }

                
                # create first schedule
                $schedule->order->schedules()->create([
                    'staff_wo_id'=>$user->id,
                    'title'=>'Next Meeting',
                    'date'=>$date,
                ]);
            }

            # send email
            Mail::to($schedule->order->user)->queue(new ScheduleReported($schedule));
        });

        return redirect(route('report.index'))->with(['message'=>"Report Added"]);
    }

    public function scheduleEdit(Schedule $schedule)
    {
        $this->authorize('update',$schedule);

        return view('dashboard.schedule.edit', compact('schedule'));
    }
    
    public function scheduleUpdate(Request $request, Schedule $schedule)
    {
        $this->authorize('update',$schedule);

        $validation = $request->validate([
            'title'=>'required||string',
            'date'=>'required|date|after_or_equal:'. Carbon::parse($schedule->date)->toDateString(),
            'location'=>'required|string',
            'note'=>'string|nullable',
        ]);

        try {
            $schedule->update($validation);

            Mail::to($schedule->order->user)->queue(new ScheduleUpdated($schedule));

            return redirect('/');
        }catch(Exception $err){
            
            return redirect()->back()->withErrors(['message'=>$err->getMessage()]);
        }
    }

    public function scheduleDelete(Schedule $schedule)
    {
        $this->authorize('delete',$schedule);
        
        $schedule->delete();
        return redirect()->back();
    }
}
