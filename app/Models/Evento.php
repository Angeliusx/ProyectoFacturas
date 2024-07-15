<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'Eventos';
    protected $primaryKey = 'CODIGO_EVENTO';
    public $timestamps = false;

    public function demandaprima()
    {
        return $this->belongsToMany(DemandaPrima::class, 'Eventos_DemandasPrima', 'CODIGO_EVENTO', 'ID_DEMANDAP')
            ->withPivot('RESOLUCION')
            ->withPivot('FECHA_EVENTO')
            ->withPivot('ID_REGISTRO')
            ->withPivot('ID_UBIPROCESO')
            ->withPivot('OBSERVACIONES')
            ->using(EventoDemandaPrima::class);
    }

    public function demandaprofuturo()
    {
        return $this->belongsToMany(DemandaProfuturo::class, 'Eventos_DemandasProfuturo', 'CODIGO_EVENTO', 'ID_DEMANDAPRO')
            ->withPivot('RESOLUCION')
            ->withPivot('FECHA_EVENTO')
            ->withPivot('ID_REGISTRO')
            ->withPivot('ID_UBICACION')
            ->withPivot('OBSERVACIONES')
            ->using(EventoDemandaProfuturo::class);
    }
}
