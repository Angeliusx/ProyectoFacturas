<?php

namespace App\Http\Livewire\Pages;

use App\Exports\DemandaProfuturoExport;
use App\Imports\DemandaProfuturoImport;
use App\Models\EventoDemandaProfuturo;
use App\Models\DemandaProfuturo;
use App\Models\Empresa;
use App\Models\Estudio;
use App\Models\Evento;
use App\Models\Deuda;
use App\Models\Actividad;
use App\Models\Registro;
use App\Models\Ubicacion;
use App\Models\Sinoe;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Excel;

class DemandasProfuturo extends Component
{
    public $excel;
    use WithFileUploads;
    use WithPagination;

    public $eventoSeleccionado, $demandasRelacionadas = [];
    public $codigoEvento, $fechaEvento, $resolucion, $idRegistro, $idUbicacion, $observaciones;
    public $verNroDemanda, $verRuc, $verTipoEmpresa, $verRazonSocial,
        $verFeEmision, $verFechaPresentacion, $verCodEstudio, $verNombreEst,
        $verCodigoExpediente, $verNroExpediente, $verAño, $verSecretario,
        $verJuzgado, $verDescripcionJuzgado, $verTotalDemandado, $verTipoDeuda,
        $delete_demanda, $add_demanda,
        $verEventosAsociados = [], $verEventosDisponibles = [], $verRegistro = [], $verUbicacion = [];


    protected $paginationTheme = 'bootstrap';
    public $busqueda = '';
    public $campoSeleccionado = 'NUM_DEMANDA';
    public $camposBusqueda = [
        'NUM_DEMANDA' => 'Nro Demanda',
        'CODIGO_UNICO_EXPEDIENTE' => 'Codigo de Expediente',
        'RUC_EMPLEADOR' => 'RUC',
        'RAZON_SOCIAL' => 'Razon Social',
    ];

    public function viewDemandaDetails($id)
    {
        $demandaprofuturo = DemandaProfuturo::with('evento')->where('ID_DEMANDAPRO', $id)->first();

        $demandasRelacionadas = DemandaProfuturo::where('CODIGO_UNICO_EXPEDIENTE', $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE)
            ->get();

        $this->demandasRelacionadas = $demandasRelacionadas;

        $this->verNroDemanda = $demandaprofuturo->NUM_DEMANDA;
        $this->verRuc = $demandaprofuturo->Empresa->RUC_EMPLEADOR;
        $this->verTipoEmpresa = $demandaprofuturo->Empresa->TIPO_EMPRESA;
        $this->verRazonSocial = $demandaprofuturo->Empresa->RAZON_SOCIAL;
        $this->verFeEmision = date('d/m/Y', strtotime($demandaprofuturo->FE_EMISION));
        $this->verFechaPresentacion =  date('d/m/Y', strtotime($demandaprofuturo->FECHA_PRESENTACION));
        $this->verCodEstudio = $demandaprofuturo->COD_ESTUDIO;
        $this->verNombreEst = $demandaprofuturo->NOMBRE_EST;
        $this->verCodigoExpediente = $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE;
        $this->verNroExpediente = $demandaprofuturo->NRO_EXPEDIENTE;
        $this->verAño = $demandaprofuturo->AÑO;
        $this->verSecretario = $demandaprofuturo->SECRETARIO;
        $this->verJuzgado = $demandaprofuturo->JUZGADO;
        $this->verDescripcionJuzgado = $demandaprofuturo->DESCRIPCION_JUZGADO;
        $this->verTotalDemandado = $demandaprofuturo->TOTAL_DEMANDADO;
        $this->verTipoDeuda = $demandaprofuturo->TIPO_DEUDA;

        $eventosAsociados = $demandaprofuturo->evento;
        $eventos = [];

        foreach ($eventosAsociados as $evento) {
            $eventos[] = [
                'resolucion_evento' => $evento->pivot->RESOLUCION,
                'codigo_evento' => $evento->CODIGO_EVENTO,
                'nombre_evento' => $evento->DESCRIPCION_EVENTO,
                'fecha_evento' =>  date('d/m/Y', strtotime($evento->pivot->FECHA_EVENTO)),
                'registro' => $evento->pivot->Registro->MODO_REGISTRO,
                'ubicacion' => $evento->pivot->Ubicacion ? $evento->pivot->Ubicacion->NOMBRE_UBICACION : 'Sin ubicación',
                'observacion_evento' => $evento->pivot->OBSERVACIONES,
            ];
        }

        $this->verEventos = array_reverse($eventos);


        $this->dispatchBrowserEvent('show-view-demanda-modal');
    }

    public function addEventoView($id)
    {
        $this->add_demanda = $id;
        $demandaprofuturo = DemandaProfuturo::with('evento')->where('ID_DEMANDAPRO', $id)->first();

        $eventosAsociados = $demandaprofuturo->evento;
        $eventosA = [];
        foreach ($eventosAsociados as $evento) {
            $eventosA[] = [
                'codigo_evento' => $evento->CODIGO_EVENTO,
                'nombre_evento' => $evento->DESCRIPCION_EVENTO,
                'resolucion_evento' => $evento->pivot->RESOLUCION,
                'fecha_evento' =>  date('d/m/Y', strtotime($evento->pivot->FECHA_EVENTO)),
                'registro' => $evento->pivot->Registro->MODO_REGISTRO,
                'ubicacion' => $evento->pivot->Ubicacion ? $evento->pivot->Ubicacion->NOMBRE_UBICACION : 'Sin ubicación',
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

        $this->verRegistro = Registro::all();
        $this->verUbicacion = Ubicacion::all();

        $this->verEventosAsociados  = array_reverse($eventosA);
        $this->verEventosDisponibles = $eventosD;

        $this->dispatchBrowserEvent('show-add-evento-modal');
    }

    public function agregarEventoDemanda()
    {

            $demandaprofuturo = DemandaProfuturo::where('ID_DEMANDAPRO', $this->add_demanda)->first();
            $this->validate([
                'codigoEvento' => 'required',
                'fechaEvento' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $eventoAnterior = EventoDemandaProfuturo::where('ID_DEMANDAPRO', $this->add_demanda)
                            ->where('FECHA_EVENTO', '>=', $value)
                            ->orderBy('FECHA_EVENTO', 'desc')
                            ->first();
        
                        if ($eventoAnterior) {
                            $fail('La fecha del evento no puede ser anterior a eventos anteriores.');
                        }
                    },
                ],
                'idRegistro' => 'required',
                'idUbicacion' => 'nullable|required_if:idRegistro,2',
                'resolucion' => 'required',
            ]);

            
            $evento = Evento::where('CODIGO_EVENTO', $this->codigoEvento)->first();
            $demandaprofuturo->evento()->attach($evento->CODIGO_EVENTO, [
                'RESOLUCION' => $this->resolucion,
                'FECHA_EVENTO' => $this->fechaEvento,
                'ID_REGISTRO' => $this->idRegistro,
                'ID_UBICACION' => !empty($this->idUbicacion) ? $this->idUbicacion : null,
                'OBSERVACIONES' => $this->observaciones,
            ]);

            $otrasDemandas = DemandaProfuturo::where('CODIGO_UNICO_EXPEDIENTE', $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE)
            ->where('ID_DEMANDAPRO', '!=', $this->add_demanda)
            ->get();
            
            
            foreach ($otrasDemandas as $otraDemanda) {
                $otraDemanda->evento()->attach($evento->CODIGO_EVENTO, [
                    'RESOLUCION' => $this->resolucion,
                    'FECHA_EVENTO' => $this->fechaEvento,
                    'ID_REGISTRO' => $this->idRegistro,
                    'ID_UBICACION' => !empty($this->idUbicacion) ? $this->idUbicacion : null,
                    'OBSERVACIONES' => $this->observaciones,
                ]);
            }

            $actividadExistente = Actividad::where('ID_DEMANDAPRO', $this->add_demanda)
            ->where('ID_USUARIO', auth()->user()->id)
            ->first();

            if ($actividadExistente) {
                $actividadExistente->delete();
            }

            $actividad = new Actividad();
            $actividad->ID_DEMANDAPRO = $this->add_demanda;
            $actividad->ID_EVENTO = $evento->CODIGO_EVENTO;
            $actividad->ID_USUARIO = auth()->user()->id;
            $actividad->ACTIVIDAD = 'Agregó el Evento ' . $evento->DESCRIPCION_EVENTO . ' a la Demanda Profuturo ' . $demandaprofuturo->NUM_DEMANDA;
            $actividad->save();

            session()->flash('success', 'Evento agregado correctamente');

            $this->dispatchBrowserEvent('close-modal');

            $this->codigoEvento = '';
            $this->fechaEvento = '';
            $this->resolucion = '';
            $this->idRegistro = '';
            $this->idUbicacion = '';
            $this->observaciones = '';

    }

    public function eliminarEvento($eventoId)
    {
        $demandaprofuturo = DemandaProfuturo::where('ID_DEMANDAPRO', $this->add_demanda)->first();
        $demandaprofuturo->evento()->detach($eventoId);

        $otrasDemandas = DemandaProfuturo::where('CODIGO_UNICO_EXPEDIENTE', $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE)
        ->where('ID_DEMANDAPRO', '!=', $this->add_demanda)
        ->get();
        foreach ($otrasDemandas as $otraDemanda) {
            $otraDemanda->evento()->detach($eventoId);
        }

        $eventosAsociados = $demandaprofuturo->evento;
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

        session()->flash('success', 'Evento eliminado correctamente');

        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id)
    {
        $this->delete_demanda = $id;
        $demandaprofuturo = DemandaProfuturo::where('ID_DEMANDAPRO', $this->delete_demanda)->first();
        // $demandasRelacionadas = DemandaProfuturo::where('CODIGO_UNICO_EXPEDIENTE', $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE)
        //     ->get();
        // $this->demandasRelacionadas = $demandasRelacionadas;
        $this->verNroDemanda = $demandaprofuturo->NUM_DEMANDA;
        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    public function deleteDemandaData()
    {
        $demandaprofuturo = DemandaProfuturo::where('ID_DEMANDAPRO', $this->delete_demanda)->first();
        // $demandasRelacionadas = DemandaProfuturo::where('CODIGO_UNICO_EXPEDIENTE', $demandaprofuturo->CODIGO_UNICO_EXPEDIENTE)->get();
    
        // foreach ($demandasRelacionadas as $demandaRelacionada) {
        //     $demandaRelacionada->evento()->detach();
        //     $demandaRelacionada->delete();
        // }
    
        $demandaprofuturo->evento()->detach();
        $demandaprofuturo->delete();

        session()->flash('error', $demandaprofuturo->NUM_DEMANDA . ' se elimino correctamente');

        $this->dispatchBrowserEvent('close-modal');

        $this->delete_demanda = '';
    }
    
    public function cancel()
    {
        $this->delete_demanda = '';
    }

    public function importar()
    {
        try {
            $this->validate([
                'excel' => 'required|mimes:xlsx,xls',
            ]);
        
            $file = $this->excel->getRealPath();
            $import = new DemandaProfuturoImport();
            Excel::import($import, $file);
            session()->flash('success', 'Datos importados correctamente.');
            $this->dispatchBrowserEvent('close-modal');
            $this->excel = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar los datos: ' . $e->getMessage());
            $this->excel = null;
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public $filtroAnos;
    public $filtroEvento;

    public function render()
    {
        $años = DemandaProfuturo::distinct()->pluck('AÑO')->toArray();
        $eventos = Evento::all();
        $query = DemandaProfuturo::query();
        
        if ($this->campoSeleccionado === 'NUM_DEMANDA') {
            $codigoUnicoExpediente = DemandaProfuturo::where('NUM_DEMANDA', $this->busqueda)
            ->value('CODIGO_UNICO_EXPEDIENTE');

            $query->where('NUM_DEMANDA', 'LIKE', '%' . $this->busqueda . '%');

            if ($codigoUnicoExpediente !== null) {
                $query->where('CODIGO_UNICO_EXPEDIENTE', 'LIKE', '%' . $codigoUnicoExpediente . '%');
            }
        } elseif (in_array($this->campoSeleccionado, ['RUC', 'RAZON_SOCIAL'])) {
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
        
        $demandaprofuturo = $query->paginate(10); // Ejecutar la consulta para obtener los resultados
        
        return view('livewire.pages.demandas-profuturo', [
            'demandaprofuturo' => $demandaprofuturo,
            'años' => $años,
            'eventos' => $eventos,  
        ]);
    }
}
