<?php

namespace App\Http\Livewire\Pages\Admin;

use App\Models\Evento;
use App\Models\DemandaPrima;
use App\Models\Empresa;
use App\Models\Estudio;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Deuda;
use App\Models\EventoDemandaPrima;
use App\Models\Actividad;
use App\Models\Estado;
use App\Models\Registro;
use Livewire\Component;

class AddDemandasPrima extends Component
{
    public $ubicaciones, $registros, $eventos, $estudios, $deudas, $empresas, $departamentos, $provincias, $distritos, $sinoe;

    public $DEPARTAMENTO, $PROVINCIA, $DISTRITO;

    public $RUC_EMPLEADOR, $RAZON_SOCIAL, $TIPO_EMPRESA, $DIRECC, $LOCALI, $REFERENCIA, $TELEFONO;
    public $COD_ESTUDIO;
    public $CODIGO_EVENTO, $FECHA_EVENTO, $RESOLUCION=0;
    public $NR_DEMANDA, $FE_EMISION, $FECHA_PRESENTACION, 
    $MTO_TOTAL_DEMANDA, $TIP_DEUDA, $CODIGO_UNICO_EXPEDIENTE, 
    $EXPEDIENTE, $AÑO, $SECRETARIO_JUZGADO, 
    $CODIGO_JUZGADO, $DESCRIPCION_JUZGADO, 
    $ID_SINOE, $ID_REGISTRO, $ID_UBICACION;

    public function mount()
    {
        $this->eventos = Evento::all();
        $this->estudios = Estudio::all();
        $this->empresas = Empresa::all();
        $this->deudas = Deuda::all();
        $this->registros = Registro::all();
        $this->departamentos = Departamento::all();
        $this->provincias = collect([]);
        $this->distritos = collect([]);
    }

    public function addDemandaPrima()
    {
        $this->validate([
            'RUC_EMPLEADOR' => 'required|size:11',
            'RAZON_SOCIAL' => 'required',
            'TIPO_EMPRESA' => 'required',
            'DIRECC' => 'required',
            'TELEFONO' => 'nullable|numeric|digits:9 ',
            'COD_ESTUDIO' => 'required',
            'CODIGO_EVENTO' => 'required',
            'NR_DEMANDA' => 'required|size:15|unique:DemandasPrima,NR_DEMANDA',
            'FE_EMISION' => 'required',
            'MTO_TOTAL_DEMANDA' => 'required',
            'TIP_DEUDA' => 'required|numeric',
            'CODIGO_UNICO_EXPEDIENTE' => 'required|unique:DemandasPrima,CODIGO_UNICO_EXPEDIENTE',
            'FECHA_PRESENTACION' => 'required|after:FE_EMISION',
            'FECHA_EVENTO' => 'required|date',
            'EXPEDIENTE' => 'required',
            'AÑO' => 'required|numeric|digits:4',
            'SECRETARIO_JUZGADO' => 'required',
            'CODIGO_JUZGADO' => 'required',
            'DESCRIPCION_JUZGADO' => 'required',
            'FECHA_EVENTO' => 'required',
            'DEPARTAMENTO' => 'required',
            'PROVINCIA' => 'required',
            'DISTRITO' => 'required',
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

        $demandasPrima = DemandaPrima::create([
            'NR_DEMANDA' => $this->NR_DEMANDA,
            'FE_EMISION' => $this->FE_EMISION,
            'RUC_EMPLEADOR' => $this->RUC_EMPLEADOR,
            'COD_ESTUDIO' => $this->COD_ESTUDIO,
            'MTO_TOTAL_DEMANDA' => $this->MTO_TOTAL_DEMANDA,
            'TIP_DEUDA' => $this->TIP_DEUDA,
            'CODIGO_UNICO_EXPEDIENTE' => $this->CODIGO_UNICO_EXPEDIENTE,
            'FECHA_PRESENTACION' => $this->FECHA_PRESENTACION,
            'EXPEDIENTE' => $this->EXPEDIENTE,
            'AÑO' => $this->AÑO,
            'SECRETARIO_JUZGADO' => $this->SECRETARIO_JUZGADO,
            'CODIGO_JUZGADO' => $this->CODIGO_JUZGADO,
            'DESCRIPCION_JUZGADO' => $this->DESCRIPCION_JUZGADO,
            'ID_ESTADO' => 1,
        ]);

        $eventoDemandasPrima = EventoDemandaPrima::create([
            'ID_DEMANDAP' => $demandasPrima->ID_DEMANDAP,
            'RESOLUCION' => $this->RESOLUCION  ,
            'CODIGO_EVENTO' => $this->CODIGO_EVENTO,
            'FECHA_EVENTO' => $this->FECHA_EVENTO,
            'ID_REGISTRO' => $this->ID_REGISTRO,
            'ID_UBICACION' => $this->ID_UBICACION,
        ]);

        $this->resetInput();

        session()->flash('success', 'Demanda Prima añadida Exitosamente');  
    
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
        $this->NR_DEMANDA = null;
        $this->FE_EMISION = null;
        $this->MTO_TOTAL_DEMANDA = null;
        $this->TIP_DEUDA = null;
        $this->CODIGO_UNICO_EXPEDIENTE = null;
        $this->EXPEDIENTE = null;
        $this->AÑO = null;
        $this->SECRETARIO_JUZGADO = null;
        $this->CODIGO_JUZGADO = null;
        $this->DESCRIPCION_JUZGADO = null;
        $this->FECHA_EVENTO = null;
        $this->DEPARTAMENTO = null;
        $this->PROVINCIA = null;
        $this->DISTRITO = null;
        $this->ID_REGISTRO = null;
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
        return view('livewire.pages.admin.add-demandas-prima');
    }
}
