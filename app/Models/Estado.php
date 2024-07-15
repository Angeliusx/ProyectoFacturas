<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'Estados';
    protected $primaryKey = 'ID_ESTADO';
    public $timestamps = false;

    public function demanda ()
    {
        return $this->hasMany(Demanda::class, 'id_estado', 'id_estado');
    }
}
