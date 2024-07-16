<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function mostrar($plantilla)
    {
        // Asegúrate de que la plantilla existe para evitar errores
        if (!view()->exists('emails.' . $plantilla)) {
            abort(404, 'Plantilla no encontrada');
        }

        // Pasa cualquier dato necesario a la vista
        $datos = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@example.com'
        ];

        return view('emails.' . $plantilla, $datos);
    }
}
