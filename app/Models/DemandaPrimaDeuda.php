<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaPrimaDeuda extends Model
{
    protected $table = 'DemandasPrima_Deudas';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ID',
        'ID_DEMANDAP',
        'TIP_DEUDA',
    ];

    public function demandaprima()
    {
        return $this->belongsTo(DemandaPrima::class, 'ID_DEMANDAP', 'ID_DEMANDAP');
    }

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'TIP_DEUDA', 'TIP_DEUDA');
    }
}
