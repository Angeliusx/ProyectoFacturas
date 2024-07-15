<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincia;

class Departamento extends Model
{
    protected $table = 'Departamentos';
    protected $primaryKey = 'ID_D';
    public $timestamps = false;

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'ID_D', 'ID_D');
    }

}
