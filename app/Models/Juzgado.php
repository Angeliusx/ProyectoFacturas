<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juzgado extends Model
{
    protected $table = 'Juzgados';
    protected $primaryKey = 'ID_JUZGADO';
    public $timestamps = false;

    protected $fillable = [
        'ID_JUZGADO',
        'ID_SJUZGADO',
        'ID_DJUZGADO',
        'CODIGO_JUZGADO',
    ];

    public function secretariojuzgado()
    {
        return $this->belongsTo(SecretarioJuzgado::class, 'ID_SJUZGADO', 'ID_SJUZGADO');
    }
    
    public function descripcionjuzgado()
    {
        return $this->belongsTo(DescripcionJuzgado::class, 'ID_DJUZGADO', 'ID_DJUZGADO');
    }

}
