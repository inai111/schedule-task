<?php

namespace App\Livewire;

use Livewire\Component;

class OrderDetail extends Component
{
    public $transDetail;

    public function mount($transDetail)
    {
        $this->transDetail = $transDetail;
    }

    public function render()
    {
        return view('livewire.order-detail');
    }
}
