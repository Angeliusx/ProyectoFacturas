<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincia;
use App\Models\Distrito;

class Provincia extends Model
{
    protected $table = 'Provincias';
    protected $primaryKey = 'ID_P';
    public $timestamps = false;

    public function distritos()
    {
        return $this->hasMany(Distrito::class, 'ID_P', 'ID_P');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'ID_D', 'ID_D');
    }

}

