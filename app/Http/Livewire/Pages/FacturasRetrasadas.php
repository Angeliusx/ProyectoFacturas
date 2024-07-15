<?php

namespace App\Http\Livewire\Pages;
use App\Models\Prediccion;

use Livewire\Component;

class FacturasRetrasadas extends Component
{
    
    public function render()
    {
        $demandaprima = Prediccion::paginate(10);

        return view('livewire.pages.facturas-retrasadas', [
            'demandaprima' => $demandaprima,
        ]);
    }
}
