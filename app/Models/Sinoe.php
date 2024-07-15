<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sinoe extends Model
{
    protected $table = 'Sinoe';
    protected $primaryKey = 'ID_SINOE';
    public $timestamps = false;

    public function demandaprima ()
    {
        return $this->hasMany(DemandaPrima::class, 'ID_SINOE', 'ID_SINOE');
    }

    public function demandaprofuturo ()
    {
        return $this->hasMany(DemandaProfuturo::class, 'ID_SINOE', 'ID_SINOE');
    }
}
