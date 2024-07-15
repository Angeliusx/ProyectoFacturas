<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbiProceso extends Model
{
    protected $table = 'Ubicacion_Procesos';
    protected $primaryKey = 'ID_UBIPROCESO';
    public $timestamps = false;

    public function demandas()
    {
        return $this->hasMany(Demanda::class, 'id_ubiproceso', 'id_ubiproceso');
    }

    public function eventosDemandasPrima()
    {
        return $this->hasMany(EventoDemandaPrima::class, 'id_ubiproceso', 'id_ubiproceso');
    }
}
