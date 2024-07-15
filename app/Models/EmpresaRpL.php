<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaRpL extends Model
{
    protected $table = 'Empresas_RpL';
    protected $primaryKey = 'ID_REPRESENTANTE';
    public $timestamps = false;

    protected $fillable = [
        'RUC_EMPLEADOR',
        'REPRESENTANTE_LEGAL',
        'RL_CORREO',
        'RL_TELEFONO',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'RUC_EMPLEADOR');
    }

}
