<?php

namespace App\Http\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\DemandaProfuturo;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Estudio;
use App\Models\Evento;
use App\Models\EventoDemandaProfuturo;
use App\Models\Empresa;
use App\Models\Actividad;
use App\Models\Estado;
use App\Models\Sinoe;
use App\Models\Registro;
use App\Models\Ubicacion;

class AddDemandasProfuturo extends Component
{
    public $ubicaciones, $registros, $sinoe, $eventos, $estudios, $departamentos, $provincias, $distritos;
    public $DEPARTAMENTO, $PROVINCIA, $DISTRITO;
    public $RUC_EMPLEADOR, $RAZON_SOCIAL, $TIPO_EMPRESA, $DIRECC, $LOCALI, $REFERENCIA, $TELEFONO;
    public $COD_ESTUDIO;
    public $CODIGO_EVENTO, $FECHA_EVENTO, $RESOLUCION=0;
    public $ID_SINOE, $ID_REGISTRO, $ID_UBICACION, $NUM_DEMANDA, $FE_EMISION, $FECHA_PRESENTACION, $NOMBRE_EST, $CODIGO_UNICO_EXPEDIENTE, $NRO_EXPEDIENTE, $AÑO, $SECRETARIO, $JUZGADO, $DESCRIPCION_JUZGADO, $TOTAL_DEMANDADO, $TIPO_DEUDA;


    public function mount()
    {
        $this->eventos = Evento::all();
        $this->estudios = Estudio::all();
        $this->departamentos = Departamento::all();
        $this->sinoe = Sinoe::all();
        $this->registros = Registro::all();
        $this->departamentos = Departamento::all();
        $this->ubicaciones = Ubicacion::all();
        $this->provincias = collect([]);
        $this->distritos = collect([]);
    }

    public function addDemandaProfuturo()
    {
        $this->validate([
            'RUC_EMPLEADOR' => 'required|size:11',
            'RAZON_SOCIAL' => 'required',
            'TIPO_EMPRESA' => 'required',
            'DIRECC' => 'required',
            'TELEFONO' => 'nullable|numeric|digits:9',
            'COD_ESTUDIO' => 'required',
            'CODIGO_EVENTO' => 'required',
            'NUM_DEMANDA' => 'required|size:15',
            'FE_EMISION' => 'required',
            'CODIGO_UNICO_EXPEDIENTE' => 'required|unique:DemandasProfuturo,CODIGO_UNICO_EXPEDIENTE',
            'FECHA_PRESENTACION' => 'required|after:FE_EMISION',
            'FECHA_EVENTO' => 'required|date',
            'NRO_EXPEDIENTE' => 'required',
            'AÑO' => 'required',
            'SECRETARIO' => 'required',
            'JUZGADO' => 'required',
            'DESCRIPCION_JUZGADO' => 'required',
            'TOTAL_DEMANDADO' => 'required',
            'TIPO_DEUDA' => 'required',
            'DEPARTAMENTO' => 'required',
            'PROVINCIA' => 'required',
            'DISTRITO' => 'required',
            'ID_SINOE' => 'required',
        ]);

        $empresaExistente = Empresa::where('RUC_EMPLEADOR', $this->RUC_EMPLEADOR)->first();

        if ($empresaExistente) {
            $empresa = $empresaExistente;
        } else {
            $empresa = Empresa::create([
                'RUC_EMPLEADOR' => $this->RUC_EMPLEADOR,
                'RAZON_SOCIAL' => $this->RAZON_SOCIAL,
                'TIPO_EMPRESA' => $this->TIPO_EMPRESA,
                'DIRECC' => $this->DIRECC,
                'LOCALI' => $this->LOCALI,
                'REFERENCIA' => $this->REFERENCIA,
                'DISTRITO' => $this->DISTRITO,
                'PROVINCIA' => $this->PROVINCIA,
                'DEPARTAMENTO' => $this->DEPARTAMENTO,
                'TELEFONO' => $this->TELEFONO,
            ]);
        }

        if ($this->COD_ESTUDIO == 109)
            {
                $this->NOMBRE_EST = 'FERNANDEZ NUÑEZ - TRUJILLO';
            }

        $demandasProfuturo = DemandaProfuturo::create([
            'NUM_DEMANDA' => $this->NUM_DEMANDA,
            'RUC_EMPLEADOR' => $this->RUC_EMPLEADOR,
            'FE_EMISION' => $this->FE_EMISION,
            'COD_ESTUDIO' => $this->COD_ESTUDIO,
            'NOMBRE_EST' => $this->NOMBRE_EST,
            'CODIGO_UNICO_EXPEDIENTE' => $this->CODIGO_UNICO_EXPEDIENTE,
            'FECHA_PRESENTACION' => $this->FECHA_PRESENTACION,
            'NRO_EXPEDIENTE' => $this->NRO_EXPEDIENTE,
            'AÑO' => $this->AÑO,
            'SECRETARIO' => $this->SECRETARIO,
            'JUZGADO' => $this->JUZGADO,
            'DESCRIPCION_JUZGADO' => $this->DESCRIPCION_JUZGADO,
            'TOTAL_DEMANDADO' => $this->TOTAL_DEMANDADO,
            'TIPO_DEUDA' => $this->TIPO_DEUDA,
            'ID_ESTADO' => 1,
            'ID_SINOE' => $this->ID_SINOE,
        ]);

        $eventoDemanasProfuturo = EventoDemandaProfuturo::create([
            'ID_DEMANDAPRO' => $demandasProfuturo->ID_DEMANDAPRO,
            'RESOLUCION' => $this->RESOLUCION  ,
            'CODIGO_EVENTO' => $this->CODIGO_EVENTO,
            'FECHA_EVENTO' => $this->FECHA_EVENTO,
            'ID_REGISTRO' => $this->ID_REGISTRO,
            'ID_UBICACION' => $this->ID_UBICACION,
        ]);

        $this->resetInput();

        //actividad
        $actividad = Actividad::create([
            'ID_DEMANDAPRO' => $demandasProfuturo->ID_DEMANDAPRO,
            'ID_USUARIO' => auth()->user()->id,
            'ACTIVIDAD' => 'Agregó la Demanda Profuturo: '. $demandasProfuturo->NUM_DEMANDA,
        ]);

        session()->flash('success', 'Demanda Profuturo añadida Exitosamente'); 
    }

    public function resetInput()
    {
        $this->RUC_EMPLEADOR = null;
        $this->RAZON_SOCIAL = null;
        $this->TIPO_EMPRESA = null;
        $this->DIRECC = null;
        $this->LOCALI = null;
        $this->REFERENCIA = null;
        $this->TELEFONO = null;
        $this->COD_ESTUDIO = null;
        $this->CODIGO_EVENTO = null;
        $this->NUM_DEMANDA = null;
        $this->FE_EMISION = null;
        $this->NOMBRE_EST = null;
        $this->CODIGO_UNICO_EXPEDIENTE = null;
        $this->NRO_EXPEDIENTE = null;
        $this->AÑO = null;
        $this->SECRETARIO = null;
        $this->JUZGADO = null;
        $this->DESCRIPCION_JUZGADO = null;
        $this->TOTAL_DEMANDADO = null;
        $this->TIPO_DEUDA = null;
        $this->DEPARTAMENTO = null;
        $this->PROVINCIA = null;
        $this->DISTRITO = null;
        $this->ID_REGISTRO = null;
        $this->ID_SINOE = null;
        $this->FECHA_PRESENTACION = null;
    }

    public function buscarEmpresa()
    {
        $empresaExistente = Empresa::where('RUC_EMPLEADOR', $this->RUC_EMPLEADOR)->first();

        if ($empresaExistente) {
            $this->RAZON_SOCIAL = $empresaExistente->RAZON_SOCIAL;
            $this->TIPO_EMPRESA = $empresaExistente->TIPO_EMPRESA;
            $this->DIRECC = $empresaExistente->DIRECC;
            $this->LOCALI = $empresaExistente->LOCALI;
            $this->REFERENCIA = $empresaExistente->REFERENCIA;
            $this->TELEFONO = $empresaExistente->TELEFONO;
            $this->DISTRITO = $empresaExistente->DISTRITO;
            $this->PROVINCIA = $empresaExistente->PROVINCIA;
            $this->DEPARTAMENTO = $empresaExistente->DEPARTAMENTO;

            $this->updatedDepartamento($empresaExistente->DEPARTAMENTO);
            $this->updatedProvincia($empresaExistente->PROVINCIA);

        } 
    }

    public function updatedDepartamento($value)
    {
        $this->provincias = Provincia::where('ID_D', $value)->get();
        $this->distritos = collect([]);
    }

    public function updatedProvincia($value)
    {
        $this->distritos = Distrito::where('ID_P', $value)->get();
    }

    public function updatedFECHAPRESENTACION($value)
    {
        $this->FECHA_EVENTO = $value;
        $this->emit('fechaPresentacionChanged', $value);
    }

    public function updatedFECHAEVENTO($value)
    {
        $this->FECHA_PRESENTACION = $value;
        $this->emit('fechaEventoChanged', $value);
    }

    public function render()
    {
        return view('livewire.pages.admin.add-demandas-profuturo');
    }
}
