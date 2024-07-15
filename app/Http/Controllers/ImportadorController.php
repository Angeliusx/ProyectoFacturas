<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ImportController extends Controller
{
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:csv,txt' // Asegúrate de validar según tus necesidades
        ]);

        try {
            Excel::import(new TuImportacion, request()->file('file')); // Asegúrate de tener la clase de importación adecuada

            return back()->with('success', 'Importación completada con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error durante la importación: ' . $e->getMessage());
        }
    }
}
