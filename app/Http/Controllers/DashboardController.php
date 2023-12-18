<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = User::find($user->id);
        
        # member biasa
        if($user->role_id === 4) {
            $orders = $user->orders()->whereIn('order_status',['pending','ongoing'])
            ->limit(3)->orderByRaw('
            CASE
                WHEN order_status="ongoing" THEN 1
                WHEN order_status="pending" THEN 2
                ELSE 3
            END ASC
            ')->get();

            $installments = $orders->first()->installments()->whereHas('transaction',function($query){
                $query->where('status','waiting');
            });

            $schedule = $orders->first()->schedules()->orderByDesc('id')->first();
            $report = $orders->first()->schedules()->orderByDesc('id')->first();

            return view('dashboard.index',compact('user','orders','installments','schedule','report'));
        }
    }
}
