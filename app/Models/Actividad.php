<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{

    protected $table = 'Actividades';
    protected $primaryKey = 'ID_ACTIVIDAD';
    public $timestamps = false;
    protected $fillable = [
        'ID_ACTIVIDAD',
        'ID_DEMANDAP',
        'ID_EVENTO',
        'ID_DEMANDAPRO',
        'ID_USUARIO',
        'ACTIVIDAD',
    ];

    public function demandaprima()
    {
        return $this->belongsTo(DemandaPrima::class, 'ID_DEMANDAP', 'ID_DEMANDAP');
    }

    public function demandaprofuturo()
    {
        return $this->belongsTo(DemandaProfuturo::class, 'ID_DEMANDAPRO', 'ID_DEMANDAPRO');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'ID_EVENTO', 'ID_EVENTO');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'ID_USUARIO', 'id');
    }

}
