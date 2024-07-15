<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediccion extends Model
{
    protected $table = 'Predicciones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'RUC_EMPLEADOR',
        'TIPO_EMPRESA',
        'MTO_TOTAL_DEMANDA',
        'DEPARTAMENTO',
        'ESTADO',
    ];
}
