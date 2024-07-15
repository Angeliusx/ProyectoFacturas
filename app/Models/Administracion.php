<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administracion extends Model
{
    protected $table = 'Administraciones';
    protected $primaryKey = 'ID_ADMINISTRACION';
    public $timestamps = false;

    protected $fillable = [
        'ID_ADMINISTRACION',
        'FECHA',
        'ZONA',
        'AFP',
        'CODIGO_FACTURA',
        'RECIBO',
        'SUBTOTAL',
        'IGV',
        'TOTAL_RECIBO',
        'DETRACCION',
        'RUC_EMPLEADOR',
        'COMISION',
        'EJECUTIVA',
        'ARANCELES',
        'ID_DEMANDAP',
        'ID_DEMANDAPRO',
        'MON_MET_INTERNA',
        'MONTOTOTAL',
        'BANCO',
        'DEP_DETRACCION',
        'OBSERVACIONES',
        'RECIBO_FACTURA',
        'ESTADO',
    ];

    public function demandaprima()
    {
        return $this->belongsTo(DemandaPrima::class, 'ID_DEMANDAP', 'ID_DEMANDAP');
    }

    public function demandaprofuturo()
    {
        return $this->belongsTo(DemandaProfuturo::class, 'ID_DEMANDAPRO', 'ID_DEMANDAPRO');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

}
