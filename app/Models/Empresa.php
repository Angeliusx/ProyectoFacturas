<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'Empresas';
    protected $primaryKey = 'RUC_EMPLEADOR';
    public $timestamps = false;

    protected $fillable = [
        'RUC_EMPLEADOR',
        'RAZON_SOCIAL',
        'ID_TIPO',
        'DIRECC',
        'LOCALI',
        'REFERENCIA',
        'DISTRITO',
        'PROVINCIA',
        'DEPARTAMENTO',
        'REPRO',
    ];

    public function demandaprima()
    {
        return $this->hasMany(DemandaPrima::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

    public function demandaprofuturo()
    {
        return $this->hasMany(DemandaProfuturo::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'DISTRITO', 'ID_DIST');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'PROVINCIA', 'ID_P');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'DEPARTAMENTO', 'ID_D');
    }

    public function registrosCorreo()
    {
        return $this->hasMany(RegistroCorreo::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

    public function representante()
    {
        return $this->hasOne(EmpresaRpL::class, 'RUC_EMPLEADOR');
    }

    public function empresaDato()
    {
        return $this->hasMany(EmpresaDato::class, 'RUC_EMPLEADOR', 'RUC_EMPLEADOR');
    }

    public function tipoempresa()
    {
        return $this->belongsTo(TipoEmpresa::class, 'ID_TIPO', 'ID_TIPO');
    }
}
