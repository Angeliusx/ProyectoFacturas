<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    protected $table = 'Demandas';
    protected $primaryKey = 'ID_DEMANDA';
    public $timestamps = false;

    protected $fillable = [
        'ID_DEMANDA',
        'COD_AFP',
        'ID_DEMANDAP',
        'ID_DEMANDAPRO',
        'ID_ESTADO',
        'ID_UBIPROCESO',
        'REPRO',
        'MTO_DEUDA_ACTUALIZADA',
    ];

    public function ubicacionProceso()
    {
        return $this->belongsTo(UbicacionProceso::class, 'id_ubiproceso', 'id_ubiproceso');
    }

    public function afp()
    {
        return $this->belongsTo(Afp::class, 'cod_afp', 'cod_afp');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function demandaPrima()
    {
        return $this->belongsTo(DemandaPrima::class, 'id_demandap', 'id_demandap');
    }

    public function demandaProfuturo()
    {
        return $this->belongsTo(DemandaProfuturo::class, 'id_demandapro', 'id_demandapro');
    }
}
