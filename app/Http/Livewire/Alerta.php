<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DemandaPrima;
use App\Models\DemandaProfuturo;
use App\Models\EventoDemandaPrima;
use App\Models\EventoDemandaProfuturo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class Alerta extends Component
{
    public $notis;

    public function render()
    {
        $fechaActual = Carbon::now();

        $demandas = DemandaPrima::whereHas('evento', function (Builder $query) use ($fechaActual) {
            $query->where('Eventos_DemandasPrima.CODIGO_EVENTO', '=', 104)
                  ->where('Eventos_DemandasPrima.FECHA_EVENTO', '<=', $fechaActual->subMonths(3));
        })
        ->whereDoesntHave('evento', function (Builder $query) {
            $query->where('Eventos_DemandasPrima.CODIGO_EVENTO', '>', 104);
        })
        ->get();
        
        return view('livewire.alerta', compact('demandas'));
    }
}
