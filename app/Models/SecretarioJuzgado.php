<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretarioJuzgado extends Model
{
    protected $table = 'Secretario_Juzgados';
    protected $primaryKey = 'ID_SJUZGADO';
    public $timestamps = false;

    protected $fillable = [
        'ID_SJUZGADO',
        'SECRETARIO_JUZGADO',
    ];

    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'ID_SJUZGADO', 'ID_SJUZGADO');
    }
}
