<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afp extends Model
{
    protected $table = 'AFP';
    protected $primaryKey = 'COD_AFP';
    public $timestamps = false;

    public function demanda()
    {
        return $this->hasMany(Demanda::class, 'cod_afp', 'cod_afp');
    }


}
