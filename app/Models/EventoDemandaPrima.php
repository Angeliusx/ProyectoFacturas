<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventoDemandaPrima extends Pivot
{
    protected $table = 'Eventos_DemandasPrima';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ID_DEMANDAP',
        'RESOLUCION',
        'CODIGO_EVENTO',
        'FECHA_EVENTO',
        'ID_REGISTRO',
        'ID_UBIPROCESO',
        'OBSERVACIONES',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'CODIGO_EVENTO', 'CODIGO_EVENTO');
    }

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'ID_REGISTRO', 'ID_REGISTRO');
    }

    public function ubiProceso()
    {
        return $this->belongsTo(UbiProceso::class, 'ID_UBIPROCESO', 'ID_UBIPROCESO');
    }

    public function demandaPrima()
    {
        return $this->belongsTo(DemandaPrima::class, 'ID_DEMANDAP', 'ID_DEMANDAP');
    }
}
