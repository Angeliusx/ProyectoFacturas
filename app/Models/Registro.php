<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table = 'Registros';
    protected $primaryKey = 'ID_REGISTRO';
    public $timestamps = false;

    public function evento_demandaprima ()
    {
        return $this->hasMany(EventoDemandaPrima::class, 'ID_REGISTRO', 'ID_REGISTRO');
    }

    public function evento_demandaprofuturo ()
    {
        return $this->hasMany(EventoDemandaProfuturo::class, 'ID_REGISTRO', 'ID_REGISTRO');
    }
    
}
