<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionTable extends Component
{
    use WithPagination;
    public $start;
    public $end;
    public $status;

    public function searchString(){}
    public function render()
    {
        $transactions = Transaction::
        orderByRaw("
        CASE
            WHEN status = 'success' THEN 0
            else 1
        END ASC
        ")->orderByDesc('updated_at');

        
        if($this->start){
            $transactions = $transactions->where('updated_at', '>=', $this->start);
        }

        if($this->end){
            $transactions = $transactions->where('updated_at', '<=', $this->start);
        }
        
        if($this->status){
            $transactions = $transactions->where('status', $this->status);
        }

        return view('livewire.transaction-table',[
            'transactions' => $transactions->simplePaginate(10)
        ]);
    }
}
