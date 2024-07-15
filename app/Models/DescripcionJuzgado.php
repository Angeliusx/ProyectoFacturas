<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescripcionJuzgado extends Model
{
    protected $table = 'Descripcion_Juzgados';
    protected $primaryKey = 'ID_DJUZGADO';
    public $timestamps = false;

    protected $fillable = [
        'ID_DJUZGADO',
        'DESCRIPCION_JUZGADO',
    ];

    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'ID_DJUZGADO', 'ID_DJUZGADO');
    }


}
