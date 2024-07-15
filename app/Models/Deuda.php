<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    protected $table = 'Deudas';
    protected $primaryKey = 'TIP_DEUDA';
    public $timestamps = false;

    public function demandaprima()
    {
        return $this->belongsToMany(DemandaPrima::class, 'DemandasPrima_Deudas', 'TIP_DEUDA', 'ID_DEMANDAP');
    }

}
