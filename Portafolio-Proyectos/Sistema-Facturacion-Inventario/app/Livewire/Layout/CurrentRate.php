<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\ExchangeRate;

class CurrentRate extends Component
{
    public function render()
    {
        $rate = ExchangeRate::where('date', date('Y-m-d'))->first();
        return view('livewire.layout.current-rate', [
            'rate' => $rate ? $rate->rate : null
        ]);
    }
}