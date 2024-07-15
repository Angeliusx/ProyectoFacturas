<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventoDemandaProfuturo extends Pivot
{
    protected $table = 'Eventos_DemandasProfuturo';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ID_DEMANDAPRO',
        'RESOLUCION',
        'CODIGO_EVENTO',
        'FECHA_EVENTO',
        'ID_REGISTRO',
        'ID_UBICACION',
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

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ID_UBICACION', 'ID_UBICACION');
    }

    public function demandaProfuturo()
    {
        return $this->belongsTo(DemandaProfuturo::class, 'ID_DEMANDAPRO', 'ID_DEMANDAPRO');
    }
}
