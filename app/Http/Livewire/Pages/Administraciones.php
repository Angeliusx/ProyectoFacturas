<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;
use App\Models\Administracion;

class Administraciones extends Component
{



    public function render()
    {
        $query = Administracion::query();

        $administracion = $query->paginate(10);

        return view('livewire.pages.administraciones', [
            'administracion' => $administracion,
        ]);
    }
}
