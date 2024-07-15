<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroCorreo extends Model
{
    protected $table = 'Registros_Correo';
    protected $primaryKey = 'ID_CORREO';
    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }   
}

