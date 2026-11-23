<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ExchangeRateManager extends Component
{
    use WithPagination;

    public $manualRate;

    public function mount()
    {
        $todayRate = ExchangeRate::where('date', date('Y-m-d'))->first();
        if ($todayRate) {
            $this->manualRate = $todayRate->rate;
        }
    }

    public function syncWithBCV()
    {
        try {
            // Using the dolarapi endpoint found in the condominio project
            $response = Http::timeout(5)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['promedio'])) {
                    $rate = $data['promedio'];
                    
                    ExchangeRate::updateOrCreate(
                        ['date' => date('Y-m-d')],
                        ['rate' => $rate]
                    );

                    $this->manualRate = $rate;
                    session()->flash('success', 'Tasa oficial BCV (USD) sincronizada correctamente: Bs. ' . $rate);
                } else {
                    session()->flash('error', 'Respuesta inesperada de la API del BCV.');
                }
            } else {
                session()->flash('error', 'Error al conectar con la API del BCV.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error de conexión: API BCV no disponible temporalmente.');
        }
    }

    public function saveManualRate()
    {
        $this->validate([
            'manualRate' => 'required|numeric|min:1'
        ], [
            'manualRate.required' => 'Debes ingresar una tasa.',
            'manualRate.numeric' => 'La tasa debe ser un número válido.',
            'manualRate.min' => 'La tasa debe ser mayor a 0.'
        ]);

        ExchangeRate::updateOrCreate(
            ['date' => date('Y-m-d')],
            ['rate' => $this->manualRate]
        );

        session()->flash('success', 'Tasa manual guardada correctamente.');
    }

    public function render()
    {
        $currentRate = ExchangeRate::where('date', date('Y-m-d'))->first();
        $history = ExchangeRate::orderBy('date', 'desc')->paginate(10);
        
        return view('livewire.settings.exchange-rate-manager', [
            'history' => $history,
            'currentRate' => $currentRate
        ]);
    }
}