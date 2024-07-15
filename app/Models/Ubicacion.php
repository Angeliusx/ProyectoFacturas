<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'Ubicaciones';
    protected $primaryKey = 'ID_UBICACION';
    public $timestamps = false;

    public function evento_demandaprima ()
    {
        return $this->hasMany(EventoDemandaPrima::class, 'ID_UBICACION', 'ID_UBICACION');
    }

    public function evento_demandaprofuturo ()
    {
        return $this->hasMany(EventoDemandaProfuturo::class, 'ID_UBICACION', 'ID_UBICACION');
    }
}
