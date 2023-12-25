<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = User::find($user->id);

        # member biasa
        switch ($user->role_id) {
            case 1:
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

                $installments = $orders->first()->installments()->whereHas('transaction', function ($query) {
                    $query->where('status', 'waiting');
                });

                $schedule = $orders->first()->schedules()->orderByDesc('id')->first();
                $report = $orders->first()->schedules()->orderByDesc('id')->first();

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
            'photo'=>'required|image|mimes:png,jpg|min:2048',
            'notes'=>'string|nullable',
            'vendors'=>'array|min:1|required',
            'vendors.*.id'=>'exists:vendor,id',
            'vendors.*.name'=>'required|string',
            'vendors.*.address'=>'required|string',
            'vendors.*.category'=>'required|string',
            'vendors.*.phone_number'=>'required|string',
            'vendors.*.bank_name'=>'required|string',
            'vendors.*.bank_account_name'=>'required|string',
            'vendors.*.bank_account_number'=>'required|string',
            'vendors.*.total_price'=>'required|string',
            'vendors.*.note'=>'required|string',
        ]);
        return view('dashboard.report.create', compact('schedule'));
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

            return redirect('/');
        }catch(Exception $err){
            
            return redirect()->back()->withErrors(['message'=>$err->getMessage()]);
        }
    }
}
