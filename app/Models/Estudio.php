<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table = 'Estudios';
    protected $primaryKey = 'COD_ESTUDIO';
    public $timestamps = false;

    public function demandaprima()
    {
        return $this->hasMany(DemandaPrima::class, 'COD_ESTUDIO', 'COD_ESTUDIO');
    }
}
