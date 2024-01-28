<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Ramsey\Uuid\Uuid;

class PayBill extends Component
{
    public $order;
    public $total;
    public $orderDetails;
    public $checks = [];
    public function mount()
    {
        $user = User::find(auth()->user()->id);
        $order = $user->orders()->where('order_status',['ongoing'])->first();
        $this->order = $order;
        # mendapatkan order detail yang sudah dibayar
        $ordDone = TransactionDetail::whereHas('transaction',function($query){
            $query->where('status','success');
        })->has('orderDetail')->select('order_detail_id as id')->pluck('id');

        $this->total = 0;
        $this->orderDetails = [];
        if($order){
            $this->orderDetails = $order->orderDetails()
            ->whereNotIn('id',$ordDone)
            ->with('vendor')->get()->toArray();
        }
    }

    public function render()
    {
        return view('livewire.pay-bill');
    }

    public function makeTransaction()
    {
        return DB::transaction(function () {
            $checks = $this->checks;
            $total = 0;
            $transactions = [];

            foreach($checks as $key => $val){
                if($val=='true'){
                    $orderDetail = OrderDetail::find($key);
                    $total += $orderDetail->total;
                    $transactions[]=[
                        'product'=>$orderDetail->vendor->name,
                        'description'=>$orderDetail->note,
                        'price'=>$orderDetail->total,
                        'order_detail_id'=>$orderDetail->id
                    ];
                }
            }

            if(count($transactions)>0){
                # update transaksi sebelumnya yang masih waiting
                $transaction = $this->order->transactions()->firstOrCreate([
                    'status'=>'waiting'
                ]);

                $update = [
                    'total'=>$total
                ];
                if(!$transaction->wasRecentlyCreated){
                    $update['slug']= Uuid::uuid1();
                    $update['exp_date']= Carbon::now()->addDay()->format('Y-m-d 00:00:00');

                }

                $transaction->update($update);

                # hapus details yang ada
                $transaction->transactionDetails()->delete();

                # buat details baru
                $transaction->transactionDetails()->createMany($transactions);

                $this->redirect(route('transaction.show',['transaction'=>$transaction->id]));
            }
        });
    }

    public function changeCheck()
    {
        $this->total = 0;
        foreach($this->checks as $key => $val){
            if($val=='true'){
                $orderDetail = OrderDetail::find($key);
                $this->total += $orderDetail->total;
            }
        }

        // $this->
    }
}
