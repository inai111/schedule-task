<?php

namespace App\Http\Controllers;

use App\Mail\ScheduleReported;
use App\Models\Order;
use App\Models\Report;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        if($user->role_id != 1){
            $reports = Report::whereHas('schedule',function($query)use($user){
                $query->where('staff_wo_id',$user->id);
            })->with('schedule')->get();
        }else{
            $reports = Report::orderByDesc('id');
            if(request('order')){
                $reports = $reports->whereHas('schedule',function($query){
                    $query->where('order_id',request('order'));
                })->with('schedule');
            }
            $reports = $reports->get();
        }

        return view('dashboard.report.index',compact('reports'));
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
    public function show(string $id)
    {
        $report = Report::with('schedule','schedule.orderDetail','schedule.orderDetail.vendor')->find($id);
        // dd($report->schedule->orderDetail);
        return view('dashboard.report.show',compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
