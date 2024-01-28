<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;

class StaffTransaksi extends Component
{
    use WithPagination;

    public $orderQ;
    public $details;
    public $total;
    public $checks;

    #[On('getOrder')]
    public function getOrder($id)
    {
        $this->orderQ = Order::with(['orderDetails' => function ($q) {
            $q->whereDoesntHave('transaction');
        }, 'orderDetails.vendor'])->find($id);
        if ($this->orderQ->orderDetails) {
            $this->details = $this->orderQ->orderDetails->toArray();
        }
    }

    public function mount()
    {
        $this->details = [];
        $this->checks = [];
        $this->total = 0;
    }

    public function render()
    {

        return view('livewire.staff-transaksi');
    }


    public function changeCheck()
    {
        $this->total = 0;
        foreach ($this->checks as $key => $val) {
            if ($val == 'true') {
                $orderDetail = OrderDetail::find($key);
                $this->total += $orderDetail->total;
            }
        }
    }


    public function makeTransaction()
    {
        try {
            return DB::transaction(function () {
                $checks = $this->checks;
                $total = 0;
                $transactions = [];

                foreach ($checks as $key => $val) {
                    if ($val == 'true') {
                        $orderDetail = OrderDetail::with('transaction')->find($key);

                        if ($orderDetail->transaction) {
                            session()->flash('message', 'Transaction added successfully');
                            return $this->dispatch('reloadPage');
                        }

                        $total += $orderDetail->total;
                        $transactions[] = [
                            'product' => $orderDetail->vendor->name,
                            'description' => $orderDetail->note,
                            'price' => $orderDetail->total,
                            'order_detail_id' => $orderDetail->id
                        ];
                    }
                }
                if (count($transactions) == 0) {
                    throw new Exception('No Transaction');
                }

                # update transaksi sebelumnya yang masih waiting
                $transaction = $this->orderQ->transactions()->create([
                    'total' => $total,
                    'slug' => Uuid::uuid1()
                ]);
                $transaction->status = 'success';
                $transaction->save();

                # buat details baru
                $transaction->transactionDetails()->createMany($transactions);

                session()->flash('message','Transaction added successfully');
                $this->dispatch('reloadPage');
            });
        } catch (Exception $e) {
            $message = $e->getMessage() ?? 'add Transaction failed';
            $this->js("toastr.error(`${message}`)");
        }
    }
}
