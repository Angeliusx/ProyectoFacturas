<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincia;
use App\Models\Empresa;

class Distrito extends Model
{
    protected $table = 'Distritos';
    protected $primaryKey = 'ID_DIST';
    public $timestamps = false;

    public function empresa()
    {
        return $this->hasMany(Empresa::class, 'DISTRITO', 'ID_DIST');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'ID_P', 'ID_P');
    }
}
