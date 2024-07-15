<?php
namespace App\Http\Livewire\Pages;

use App\Models\Actividad;
use App\Models\User; // Asegúrate de importar el modelo User si no lo has hecho ya
use Livewire\Component;
use Livewire\WithPagination;

class Actividades extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $idUsuarioFiltro; // Agregamos una propiedad para almacenar el ID_USUARIO seleccionado
    public $busqueda = '';
    
    public function render()
    {
        $query = Actividad::query();

        if ($this->idUsuarioFiltro) {
            $query->where('ID_USUARIO', $this->idUsuarioFiltro);
        }

        $query->where('ACTIVIDAD', 'like', "%{$this->busqueda}%");


        $actividades = $query->orderBy('ID_ACTIVIDAD', 'desc')->paginate(10);

        $opcionesID_USUARIO = User::all(); // Obtener opciones para el select de ID_USUARIO

        return view('livewire.pages.actividades', [
            'actividades' => $actividades,
            'opcionesID_USUARIO' => $opcionesID_USUARIO,
        ]);
    }
}
