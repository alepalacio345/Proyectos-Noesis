<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ExchangeRateWidget extends Component
{
    public function syncWithBCV()
    {
        try {
            $response = Http::timeout(5)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['promedio'])) {
                    $rate = $data['promedio'];
                    
                    ExchangeRate::updateOrCreate(
                        ['date' => date('Y-m-d')],
                        ['rate' => $rate]
                    );

                    session()->flash('widget_success', 'Tasa actualizada: Bs. ' . $rate);
                } else {
                    session()->flash('widget_error', 'Respuesta inesperada.');
                }
            } else {
                session()->flash('widget_error', 'Error API BCV.');
            }
        } catch (\Exception $e) {
            session()->flash('widget_error', 'API no disponible.');
        }
    }

    public function render()
    {
        $currentRate = ExchangeRate::where('date', date('Y-m-d'))->first();
        return view('livewire.settings.exchange-rate-widget', [
            'currentRate' => $currentRate
        ]);
    }
}