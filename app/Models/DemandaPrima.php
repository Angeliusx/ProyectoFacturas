<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaPrima extends Model
{
    protected $table = 'DemandasPrima';
    protected $primaryKey = 'ID_DEMANDAP';
    public $timestamps = false;

    protected $fillable = [
        'ID_DEMANDAP',
        'NR_DEMANDA',
        'FE_EMISION',
        'RUC_EMPLEADOR',
        'COD_ESTUDIO',
        'MTO_TOTAL_DEMANDA',
        'TIP_DEUDA',
        'CODIGO_UNICO_EXPEDIENTE',
        'FECHA_PRESENTACION',
        'EXPEDIENTE',
        'AÑO',
        'CODIGO_JUZGADO',
        'ID_SJUZGADO',
        'ID_DJUZGADO',
        'ID_UBIPROCESO',
    ];

     public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

    public function evento()
    {
        return $this->belongsToMany(Evento::class, 'Eventos_DemandasPrima', 'ID_DEMANDAP', 'CODIGO_EVENTO')
            ->withPivot('RESOLUCION')
            ->withPivot('FECHA_EVENTO')
            ->withPivot('ID_REGISTRO')
            ->withPivot('ID_UBIPROCESO')
            ->withPivot('OBSERVACIONES')
            ->using(EventoDemandaPrima::class);
    }

    public function estudio()
    {
        return $this->belongsTo(Estudio::class, 'COD_ESTUDIO', 'COD_ESTUDIO');
    }

    public function deuda()
    {
        return $this->belongsToMany(Deuda::class, 'DemandasPrima_Deudas', 'ID_DEMANDAP', 'TIP_DEUDA');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'ID_ESTADO', 'ID_ESTADO');
    }

    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'ID_JUZGADO', 'ID_JUZGADO');
    }

    public function demanda()
    {
        return $this->hasOne(Demanda::class, 'id_demandap', 'id_demandap');
    }
}
