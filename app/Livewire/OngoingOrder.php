<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OngoingOrder extends Component
{
    use WithPagination;
    public $search;
    public $checks;

    public function searchString(){}

    public function render()
    {
        $orders = Order::orderByRaw("
        CASE
            WHEN order_status = 'ongoing' AND total_price > 0 THEN 0
            WHEN order_status = 'ongoing' THEN 1
            WHEN order_status = 'success' THEN 2
            else 3
        END ASC
        ")->orderByDesc('updated_at');

        if($this->search){
            $search = $this->search;
            $orders = $orders->where(function ($order) use ($search){
                $order->where('city','like',"%{$search}%")
                ->orWhereHas('user',function ($user) use ($search){
                    $user->where('email','like',"%{$search}%");
                });
            });
        }

        $orders = $orders->with(['transactions'=>function($query){
            return $query->where('status','success');
        },'orderDetails','orderDetails.transaction','orderDetails.vendor'])->simplePaginate(10);
        foreach($orders as $order){
            # dapatkan order detail yang sudah di bayar dari transaksi detail
            // $paidVendor = $order->orderDetails;
            $order->bill = $order->total_price;
            foreach($order->orderDetails as $item){
                if($item->transaction){
                    $order->total_price -= $item->transaction->total;
                }
            }
        }

        return view('livewire.ongoing-order',[
            'orders' => $orders
        ]);
    }

    public function emitTo($id)
    {
        $this->dispatch('getOrder',$id);
    }
}
