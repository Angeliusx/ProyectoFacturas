<?php

namespace App\Http\Livewire\Pages;

use App\Exports\DemandaPrimaExport;
use App\Imports\DemandaPrimaImport;
use App\Models\EventoDemandaPrima;
use App\Models\SecretarioJuzgado;
use App\Models\DescripcionJuzgado;
use App\Models\DemandaPrima;
use App\Models\DemandaPrimaDeuda;
use App\Models\Empresa;
use App\Models\Estudio;
use App\Models\Evento;
use App\Models\Deuda;
use App\Models\Actividad;
use App\Models\Registro;
use App\Models\UbiProceso;
use App\Models\Estado;
use App\Models\Sinoe;
use App\Models\Juzgado;
use App\Models\Demanda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Excel;



class DemandasPrima extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $eventoSeleccionado;
    public $codigoEvento, $fechaEvento, $resolucion, $idRegistro, $idUbiProceso, $observaciones;

    public $excel;
    
     public $verNroDemanda, $verTipoEmpresa, $verRuc, $verRazonSocial, 
     $verCodigoExpediente, $verFechaEmision, $verFechaPresentacion, $verCodigoEstudio, 
     $verNombreEstudio, $verSecretarioJuzgado, $verDescripcionJuzgado, $verCodigoEvento, 
     $verNombreEvento, $verFechaEvento, $verMontoTotal, $verTipoDeudad, 
     $verCodigoJuzgado, $verCodigoDeuda, $verNombreDeuda, $verEstado,
     $delete_demanda, $demanda_prim;
    // $verEventosAsociados = [], $verEventosDisponibles = [], $verRegistro = [], $verUbicacion = [];

    public $filtros = [
        'AÑO' => [],
        'COD_ESTUDIO' => [],
        'TIPO_EMPRESA' => [],
        'TIP_DEUDA' => [],
        
    ];

    public $opciones = [
        'AÑO' => ['2023', '2022', '2021'],
        'COD_ESTUDIO' => ['Opción A', 'Opción B', 'Opción C'],
        'TIPO_EMPRESA' => ['Opción X', 'Opción Y', 'Opción Z'],
        'TIP_DEUDA' => ['Opción 1', 'Opción 2', 'Opción 3'],
    ];

    protected $paginationTheme = 'bootstrap';
    public $busqueda = '';
    public $campoSeleccionado = 'NR_DEMANDA';
    public $camposBusqueda = [
        'NR_DEMANDA' => 'Nro Demanda',
        'CODIGO_UNICO_EXPEDIENTE' => 'Codigo de Expediente',
        'RUC_EMPLEADOR' => 'RUC',
        'RAZON_SOCIAL' => 'Razon Social',

    ];

    public function agregarFiltro($tipo)
    {
        $this->filtros[$tipo] = null;
    }

    public $idprima, $nr_demanda, $fe_emision,$ruc_empleador,$nombre_estudio, $cod_estudio, $mto_total_demanda, $codigo_unico_expediente, $fecha_presentacion, $expediente, $año, $id_juzgado, $descripcion_juzgado, $secretario_juzgado, $codigo_juzgado;
    public $razon_social, $nombre_tipo, $direcc, $locali, $referencia, $distrito, $provincia, $departamento, $repro;
    public $estado, $descripcion_estado, $id_ubiproceso, $deudaTotal = [], $eventoTotal = [];

    public function viewDemandaDetails($id)
    {
        $demandaprima = DemandaPrima::with('evento')->where('ID_DEMANDAP', $id)->first();
        $this->idprima = $id;
        $this->nr_demanda = $demandaprima->NR_DEMANDA;
        $this->fe_emision =date('d/m/Y', strtotime($demandaprima->FE_EMISION));
        
        $this->ruc_empleador = (string)($demandaprima->Empresa->RUC_EMPLEADOR);
        
            $empresa = Empresa::where('RUC_EMPLEADOR', (string)($demandaprima->Empresa->RUC_EMPLEADOR))->first();
            $this->razon_social = $empresa->RAZON_SOCIAL;
            $this->nombre_tipo = $empresa->TipoEmpresa->NOMBRE_TIPO;
        $this->cod_estudio = $demandaprima->Estudio->COD_ESTUDIO;
        $this->nombre_estudio = $demandaprima->Estudio->NOMBRE_EST;
        $this->mto_total_demanda = $demandaprima->MTO_TOTAL_DEMANDA ?? 'NA';
        $this->codigo_unico_expediente = $demandaprima->CODIGO_UNICO_EXPEDIENTE ?? 'NA';
        $this->fecha_presentacion = isset($demandaprima->FECHA_PRESENTACION) && strtotime($demandaprima->FECHA_PRESENTACION) !== false 
        ? date('d/m/Y', strtotime($demandaprima->FECHA_PRESENTACION)) 
        : 'NA';
        $this->expediente = $demandaprima->EXPEDIENTE ?? 'NA';
        $this->año = $demandaprima->AÑO ?? 'NA';
            $juzgado = Juzgado::where('ID_JUZGADO', $demandaprima->ID_JUZGADO)->first();
            $this->secretario_juzgado = $juzgado->SecretarioJuzgado->SECRETARIO_JUZGADO ?? 'NA';
            $this->descripcion_juzgado = $juzgado->DescripcionJuzgado->DESCRIPCION_JUZGADO ?? 'NA';
            $this->codigo_juzgado = $juzgado->CODIGO_JUZGADO ?? 'NA';
        $demanda=Demanda::where('ID_DEMANDAP', $id)->first();
        $this->descripcion_estado = $demanda->ID_ESTADO;
        $this->id_ubiproceso = $demanda->ID_UBIPROCESO ?? 'NA';
        $this->repro = $demanda->REPRO ?? 'NA';
        $deudasAsociados = DemandaPrimaDeuda::where('ID_DEMANDAP', $id)->get();
        $deudas = [];
        foreach ($deudasAsociados as $deuda) {
            if($deuda->Deuda->TIP_DEUDA == 1 || $deuda->Deuda->TIP_DEUDA == 2){
                $sub_deuda = "PRESUNTA";
            }else{
                $sub_deuda = "REAL";
            }
            $deudas[] = [
                'tip_deuda' => $deuda->Deuda->TIP_DEUDA,
                'descripcion_deuda' => $deuda->Deuda->DESCRIPCION_DEUDA,
                'sub_deuda' => $sub_deuda,
            ];
        }
        $this->deudaTotal = $deudas;
        $eventosAsociados = $demandaprima->evento;
        $eventos = [];
        foreach ($eventosAsociados as $evento) {
            $eventos[] = [
                'resolucion_evento' => $evento->pivot->RESOLUCION ?? 'NA',
                'codigo_evento' => $evento->CODIGO_EVENTO ?? 'NA',
                'nombre_evento' => $evento->DESCRIPCION_EVENTO ?? 'NA',
                'fecha_evento' =>  date('d/m/Y', strtotime($evento->pivot->FECHA_EVENTO)) ?? 'NA',
                'registro' => $evento->pivot->Registro->MODO_REGISTRO ?? 'NA',
                'ubiproceso' => $evento->pivot->UbiProceso->UBICACION_PROCESO ?? 'NA',
                'observacion_evento' => $evento->pivot->OBSERVACIONES ?? 'NA',
            ];
        }

        $this->eventoTotal = array_reverse($eventos);

        $this->dispatchBrowserEvent('show-view-demanda-modal');
    }

    public $verEventosDisponibles = [], $verEventosAsociados = [], $registro = [], $ubiproceso = [], $idUbiproceso;

    public function addEventoView($id)
    {
        $this->demanda_prim = $id;
        $demandaprima = DemandaPrima::with('evento')->where('ID_DEMANDAP', $id)->first();

        $eventosAsociados = $demandaprima->evento;
        $eventosA = [];
        foreach ($eventosAsociados as $evento) {
            $eventosA[] = [
                'codigo_evento' => $evento->CODIGO_EVENTO,
                'nombre_evento' => $evento->DESCRIPCION_EVENTO,
                'resolucion_evento' => $evento->pivot->RESOLUCION,
                'fecha_evento' =>  date('d/m/Y', strtotime($evento->pivot->FECHA_EVENTO)),
                'registro' => $evento->pivot->Registro->MODO_REGISTRO,
                'ubiproceso' => $evento->pivot->UbiProceso->UBICACION_PROCESO ?? '',
                'observacion_evento' => $evento->pivot->OBSERVACIONES,
            ];
        }

        $eventosDisponibles = Evento::whereNotIn('CODIGO_EVENTO', $eventosAsociados->pluck('CODIGO_EVENTO')->toArray())
        ->orWhere('CODIGO_EVENTO', '0')
        ->get();
        $eventosD = [];
        foreach ($eventosDisponibles as $evento) {
            $eventosD[] = [
                'codigo' => $evento->CODIGO_EVENTO,
                'nombre' => $evento->DESCRIPCION_EVENTO,
            ];
        }

        $this->registro = Registro::all();
        $this->ubiproceso = UbiProceso::all();

        $this->verEventosAsociados  = array_reverse($eventosA);
        $this->verEventosDisponibles = $eventosD;

        $this->dispatchBrowserEvent('show-add-evento-modal');
    }

    public function agregarEventoDemanda()
    {
        $this->validate([
            'codigoEvento' => 'required',
            'fechaEvento' => [
                'required',
                function ($attribute, $value, $fail) {
                    $eventoAnterior = EventoDemandaPrima::where('ID_DEMANDAP', $this->demanda_prim)
                        ->where('FECHA_EVENTO', '>', $value)
                        ->orderBy('FECHA_EVENTO', 'desc')
                        ->first();
    
                    if ($eventoAnterior) {
                        $fail('La fecha del evento no puede ser anterior a eventos anteriores.');
                    }
                },
            ],
            'idRegistro' => 'required',
            'idUbiProceso' => 'nullable|required_if:idRegistro,2',
            'resolucion' => 'required',
        ]);

        $evento = Evento::where('CODIGO_EVENTO', $this->codigoEvento)->first();
        $demandaprima = DemandaPrima::where('ID_DEMANDAP', $this->demanda_prim)->first();
        $demandaprima->evento()->attach($evento->CODIGO_EVENTO, [
            'RESOLUCION' => $this->resolucion,
            'FECHA_EVENTO' => $this->fechaEvento,
            'ID_REGISTRO' => $this->idRegistro,
            'ID_UBIPROCESO' => !empty($this->idUbiProceso) ? $this->idUbiProceso : null,
            'OBSERVACIONES' => $this->observaciones,
        ]);

        //actividad

        $actividadExistente = Actividad::where('ID_DEMANDAP', $this->demanda_prim)
        ->first();

        if ($actividadExistente) {
            $actividadExistente->delete();
        }

        $actividad = new Actividad();
        $actividad->ID_DEMANDAP = $this->demanda_prim;
        $actividad->ID_EVENTO = $evento->CODIGO_EVENTO;
        $actividad->ID_USUARIO = auth()->user()->id;
        $actividad->ACTIVIDAD = 'Agregó el Evento ' . $evento->DESCRIPCION_EVENTO . ' Entró a la Demanda Prima ' . $demandaprima->NR_DEMANDA;
        $actividad->save();


        session()->flash('success', 'Evento agregado correctamente');
        $this->dispatchBrowserEvent('close-modal');

        $this->codigoEvento = '';
        $this->fechaEvento = '';
        $this->resolucion = '';
        $this->idRegistro = '';
        $this->idUbiproceso = '';
        $this->observaciones = '';

    }

    public $empresasTotal = [], $estadoTotal = [], $estudioTotal=[], $secretarioTotal=[], $descripcionTotal=[];

    public function editDemanda($id)
    {
        $this->secretarioTotal = SecretarioJuzgado::all();
        $this->descripcionTotal = DescripcionJuzgado::all();

        $demandaprima = DemandaPrima::where('ID_DEMANDAP', $id)->first();
        $this->demanda_prim = $id;
        $this->nr_demanda = $demandaprima->NR_DEMANDA;
        $this->fe_emision = $demandaprima->FE_EMISION;
        $this->estudioTotal = Estudio::all();
        $this->cod_estudio = $demandaprima->COD_ESTUDIO;
        $this->mto_total_demanda = $demandaprima->MTO_TOTAL_DEMANDA;
        $this->codigo_unico_expediente = $demandaprima->CODIGO_UNICO_EXPEDIENTE;
        $this->fecha_presentacion = $demandaprima->FECHA_PRESENTACION;
        $this->expediente = $demandaprima->EXPEDIENTE;
        $this->año = $demandaprima->AÑO;
        $juzgado=Juzgado::where('ID_JUZGADO', $demandaprima->ID_JUZGADO)->first();
        $this->secretario_juzgado = $juzgado->SecretarioJuzgado->SECRETARIO_JUZGADO ?? 'NA';
        $this->descripcion_juzgado = $juzgado->DescripcionJuzgado->DESCRIPCION_JUZGADO ?? 'NA';
        $this->codigo_juzgado = $juzgado->CODIGO_JUZGADO ?? 'NA';
        $this->estadoTotal=Estado::all();
        $demanda=Demanda::where('ID_DEMANDAP', $id)->first();
        $this->estado = $demanda->ID_ESTADO;
        $this->id_ubiproceso = $demandaprima->ID_UBIPROCESO;
        
        $this->dispatchBrowserEvent('show-edit-demanda-modal');
    }

    public function updateDemanda()
    {
        $this->validate([
            'nr_demanda' => 'required',
            'estado' => 'required',
            'codigo_unico_expediente' => 'required',
        ]);

        try {
            $demandaprima = DemandaPrima::where('ID_DEMANDAP', $this->demanda_prim)->first();
            $demandaprima->NR_DEMANDA = $this->nr_demanda;
            $demandaprima->COD_ESTUDIO = $this->cod_estudio;
            $demandaprima->MTO_TOTAL_DEMANDA = $this->mto_total_demanda;
            $demandaprima->CODIGO_UNICO_EXPEDIENTE = $this->codigo_unico_expediente;
            $demandaprima->FECHA_PRESENTACION = $this->fecha_presentacion;
            $demandaprima->EXPEDIENTE = $this->expediente;
            $demandaprima->AÑO = $this->año;

            $secretario = SecretarioJuzgado::where('SECRETARIO_JUZGADO', $this->secretario_juzgado)->first();
            $descripcion = DescripcionJuzgado::where('DESCRIPCION_JUZGADO', $this->descripcion_juzgado)->first();
            if (!$secretario) {
                $secretario = SecretarioJuzgado::create([
                    'SECRETARIO_JUZGADO' => $this->secretario_juzgado,
                ]);
            }
            if (!$descripcion) {
                $descripcion = DescripcionJuzgado::create([
                    'DESCRIPCION_JUZGADO' => $this->descripcion_juzgado,
                ]);
            }
            $juzgado = Juzgado::Create([
                'CODIGO_JUZGADO' => $this->codigo_juzgado,
                'ID_SJUZGADO' => $secretario->ID_SJUZGADO,
                'ID_DJUZGADO' => $descripcion->ID_DJUZGADO,
            ]);
            $demandaprima->ID_JUZGADO = $juzgado->ID_JUZGADO;
            $demandaprima->save();

            $demanda = Demanda::where('ID_DEMANDAP', $this->demanda_prim)->first();
            $demanda->ID_ESTADO = $this->estado;
            $demanda->save();

            session()->flash('success', 'Demanda actualizada correctamente');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la Demanda Prima: ' . $e->getMessage());
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public function eliminarEvento($eventoId , $observaciones)
    {
        $demandaprima = DemandaPrima::findOrFail($this->demanda_prim);

        if ($eventoId == 0 && $observaciones !== null) {
            $demandaprima->evento()
                ->wherePivot('CODIGO_EVENTO', $eventoId)
                ->wherePivot('OBSERVACIONES', $observaciones)
                ->detach($eventoId);
        } else {
            $demandaprima->evento()->detach($eventoId);
        }
        session()->flash('success', 'Evento eliminado correctamente');

        $eventosAsociados = $demandaprima->evento;
        $eventosA = [];
        foreach ($eventosAsociados as $evento) {
            $eventosA[] = [
                'codigo_evento' => $evento->CODIGO_EVENTO,
                'nombre_evento' => $evento->DESCRIPCION_EVENTO,
                'resolucion_evento' => $evento->pivot->RESOLUCION,
                'fecha_evento' => $evento->pivot->FECHA_EVENTO,
                'registro' => $evento->pivot->ID_REGISTRO,
                'ubicacion' => $evento->pivot->ID_UBICACION ?? null,
                'observacion_evento' => $evento->pivot->OBSERVACIONES,
            ];
        }
        $this->verEventosAsociados = $eventosA;

        $eventosDisponibles = Evento::whereNotIn('CODIGO_EVENTO', $eventosAsociados->pluck('CODIGO_EVENTO')->toArray())->get();
        $eventosD = [];
        foreach ($eventosDisponibles as $evento) {
            $eventosD[] = [
                'codigo' => $evento->CODIGO_EVENTO,
                'nombre' => $evento->DESCRIPCION_EVENTO,
            ];
        }
        $this->verEventosDisponibles = $eventosD;

        //actividad

        $actividadExistente = Actividad::where('ID_DEMANDAP', $this->demanda_prim)
            ->first();

        if ($actividadExistente) {
            $actividadExistente->delete();
        }

        $actividad = new Actividad();
        $actividad->ID_DEMANDAP = $this->demanda_prim;
        $actividad->ID_EVENTO = $eventoId;
        $actividad->ID_USUARIO = auth()->user()->id;
        $actividad->ACTIVIDAD = 'Eliminó el Evento ' . $evento->DESCRIPCION_EVENTO . ' de la Demanda Prima ' . $demandaprima->NR_DEMANDA;
        $actividad->save();


        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id)
    {
        $this->delete_demanda = $id;
        $demandaprima = DemandaPrima::where('ID_DEMANDAP', $this->delete_demanda)->first();
        $this->verNroDemanda = $demandaprima->NR_DEMANDA;
        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    public function deleteDemandaData()
    {
        $demandaprima = DemandaPrima::where('ID_DEMANDAP', $this->delete_demanda)->first();
        $demandaprima->evento()->detach();
        $actividad = Actividad::where('ID_DEMANDAP', $this->delete_demanda);
        if ($actividad) {
            $actividad->delete();
        }
        $demandaprima->delete();

        session()->flash('error', $demandaprima->NR_DEMANDA . ' se elimino correctamente');

        $this->dispatchBrowserEvent('close-modal');

        $this->delete_demanda = '';
    }

    public function descargarInfo($id)
    {
        try{
            $demandaprima = DemandaPrima::with('evento')->where('ID_DEMANDAP', $id)->first();
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $text = $section->addText('Demanda Prima: '.$demandaprima->NR_DEMANDA);
            $text = $section->addText('RUC: '. $demandaprima->Empresa->RUC_EMPLEADOR);
            $text = $section->addText('Tipo de Empresa: '. $demandaprima->Empresa->TIPO_EMPRESA);
            $text = $section->addText('Razon Social: '. $demandaprima->Empresa->RAZON_SOCIAL);
            $text = $section->addText('Codigo de Expediente: '. $demandaprima->CODIGO_UNICO_EXPEDIENTE);
            $text = $section->addText('Fecha de Emision: '. date('d/m/Y', strtotime($demandaprima->FE_EMISION)));
            $text = $section->addText('Fecha de Presentacion: '. date('d/m/Y', strtotime($demandaprima->FECHA_PRESENTACION)));



            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = $demandaprima->NR_DEMANDA . '.docx';
            $objWriter->save($fileName);
            session()->flash('success', $demandaprima->NR_DEMANDA . ' se descargó correctamente');
            return response()->download(public_path($fileName))->deleteFileAfterSend(true);
            
        }
        catch (\Exception $e) {
            session()->flash('error', 'Error al importar los datos: ' . $e->getMessage());
            $this->dispatchBrowserEvent('close-modal');
        }


    }
    
    public function cancel()
    {
        $this->delete_demanda = '';
    }

    public function exportar()
    {
        try {
            $años = DemandaPrima::distinct()->pluck('AÑO')->toArray();
            $eventos = Evento::all();
            $query = DemandaPrima::query();
            

            if ($this->filtroAnos && in_array($this->filtroAnos, $años)) {
                $query->where('AÑO', $this->filtroAnos);
            }

            if ($this->filtroEvento) {
                $query->whereHas('evento', function ($query) {
                    $query->where('Eventos.CODIGO_EVENTO', $this->filtroEvento);
                });
            }

            $data = $query->get();

            // Construir nombre del archivo con filtros
            $nombreArchivo = 'demandas_prima';
            if (!empty($this->filtroAnos)) {
                $nombreArchivo .= '_ano_' . $this->filtroAnos;
            }
            if (!empty($this->filtroEvento)) {
                $nombreArchivo .= '_evento_' . $this->filtroEvento;
            }
            
            // Limpiar el nombre del archivo
            $nombreArchivo = preg_replace("/[^A-Za-z0-9_\-]/", "", $nombreArchivo);

            $nombreArchivo .= '.xlsx';

            return Excel::download(new DemandaPrimaExport($data), $nombreArchivo, \Maatwebsite\Excel\Excel::XLSX);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al exportar los datos: ' . $e->getMessage());
            $this->dispatchBrowserEvent('close-modal');
        }
    }


    public $loading = false;
    public $demandasNoImportadas = [];

    public function importar()
    {
        try {
            $this->loading = true;
            $this->validate([
                'excel' => 'required|mimes:xlsx,xls',
            ]);
            $file = $this->excel->getRealPath();
            Excel::import(new DemandaPrimaImport, $file);   
            session()->flash('success', 'Datos importados correctamente.'); 
            $this->excel = null;
            $this->dispatchBrowserEvent('close-modal');
            $import = new DemandaPrimaImport();
            $this->demandasNoImportadas = $import->getDemandasNoImportadas(); 
        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar los datos: ' . $e->getMessage());
            $this->excel = null;
            $this->dispatchBrowserEvent('close-modal'); 
        } finally {
            $this->loading = false; // Desactivar el estado de carga
        }
        
    }

    public function logs()
    {
        return $this->descargarDemandasNoImportadas();
    }

    public function descargarDemandasNoImportadas()
    {
        // $import = new DemandaPrimaImport();
        
        if (!empty($this->demandasNoImportadas)) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $text = $section->addText('Demandas no importadas');
            foreach ($this->demandasNoImportadas as $demanda) {
                $text = $section->addText($demanda);
            }
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = 'demandas_no_importadas.docx';
            $objWriter->save($fileName);

            $this->demandasNoImportadas = [];
            return response()->download(public_path($fileName))->deleteFileAfterSend(true);
        } else {
            session()->flash('error', 'No hay demandas que no fueron importadas para descargar.');
            return null;
        }
    }




    public $filtroAnos;
    public $filtroEvento;

    public function render()
    {
        $años = DemandaPrima::distinct()->pluck('AÑO')->toArray();
        $eventos = Evento::all();
        $query = DemandaPrima::query();

        if (in_array($this->campoSeleccionado, ['RUC', 'RAZON_SOCIAL'])) {
            $query->whereHas('empresa', function ($query) {
                $query->where($this->campoSeleccionado, 'LIKE', '%' . $this->busqueda . '%');
            });
        } else {
            $query->where($this->campoSeleccionado, 'LIKE', '%' . $this->busqueda . '%');
        }

        if ($this->filtroAnos && in_array($this->filtroAnos, $años)) {
            $query->where('AÑO', $this->filtroAnos);
        }

        if ($this->filtroEvento) {
            $query->whereHas('evento', function ($query) {
                $query->where('Eventos.CODIGO_EVENTO', $this->filtroEvento);
            });
        }

        $demandaprima = $query->paginate(10);
        

        return view('livewire.pages.demandas-prima', [
            'demandaprima' => $demandaprima,
            'años' => $años,
            'eventos' => $eventos,
        ]);
    }

    public function mount()
    {
        $this->codEstudios = Estudio::pluck('NOMBRE_EST', 'COD_ESTUDIO');
        $this->codDeuda = Deuda::pluck('DESCRIPCION_DEUDA', 'TIP_DEUDA');
        $this->codEvento = Evento::pluck('DESCRIPCION_EVENTO', 'CODIGO_EVENTO');
    }
}
