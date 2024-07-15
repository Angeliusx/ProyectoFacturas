<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpresa extends Model
{
    protected $table = 'Tipo_Empresas';
    protected $primaryKey = 'ID_TIPO';
    public $timestamps = false;

    public function empresa ()
    {
        return $this->hasMany(Empresa::class, 'ID_TIPO', 'ID_TIPO');
    }
}
