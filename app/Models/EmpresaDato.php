<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaDato extends Model
{
    protected $table = 'Empresas_Datos';
    protected $primaryKey = 'RUC_EMPLEADOR';
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'RUC_EMPLEADOR',
        'TELEFONO',
        'CORREO',
        'NOMBRE',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }   
}
