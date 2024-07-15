<?php

namespace App\Http\Livewire\Pages;

use App\Models\Empresa;
use App\Models\EmpresaRpL;
use App\Models\EmpresaDato;
use App\Models\Departamento;
use App\Models\RegistroCorreo;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Component;
use App\Imports\EmpresaImport;
use Illuminate\Support\Facades\Mail;
use App\Mail\Correo;
use Excel;


class Empresas extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $excel, $correoMensaje, $correoAsunto, $correoFirma;
    protected $paginationTheme = 'bootstrap';
    public $enviarWhatsapp = false;
    public $busqueda = '';
    public $campoSeleccionado = 'RUC_EMPLEADOR';
    public $camposBusqueda = [
        'RUC_EMPLEADOR' => 'RUC',
        'RAZON_SOCIAL' => 'Razon Social',

    ];

    public $sortBy = 'RUC_EMPLEADOR';
    public $sortDirection = 'asc';
    public $filtroTipoEmpresa;
    public $filtroDepartamento;
    public function render()
    {
        $query = Empresa::with('representante', 'departamento');
        $tipos = Empresa::select('ID_TIPO')->distinct()->get()->pluck('ID_TIPO')->toArray();
        $departamentos = Departamento::select('DEPARTAMENTO')->distinct()->get()->pluck('DEPARTAMENTO')->toArray();


        if ($this->busqueda) {
            $query->where($this->campoSeleccionado, 'LIKE', '%' . $this->busqueda . '%');
        }

        if ($this->filtroTipoEmpresa && in_array($this->filtroTipoEmpresa, $tipos)) {
            $query->where('ID_TIPO', $this->filtroTipoEmpresa);
        }

        if ($this->filtroDepartamento && in_array($this->filtroDepartamento, $departamentos)) {
            $query->whereHas('departamento', function ($q) {
                $q->where('DEPARTAMENTO', $this->filtroDepartamento);
            });
        }

        // Agregar ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        $empresas = $query->paginate(15);

        return view('livewire.pages.empresas', [
            'empresas' => $empresas,
            'tipos' => $tipos,
            'departamentos' => $departamentos,
        ]);

    }

    public function sortBy($field)
    {
        if ($field !== 'id') {
            if ($this->sortBy === $field) {
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortDirection = 'asc';
            }

            $this->sortBy = $field;
        }
    }

    public $rucSeleccionados = [];

    public function toggleRucSeleccionado($ruc)
    {
        if (in_array($ruc, $this->rucSeleccionados)) {
            $key = array_search($ruc, $this->rucSeleccionados);
            unset($this->rucSeleccionados[$key]);
        } else {
            $this->rucSeleccionados[] = $ruc;
        }
    }

    public $verRegistrosAsociados = [];

    public function viewEnvios($id)
    {
        $empresa = Empresa::where('RUC_EMPLEADOR', $id)->first();
        
        // Verificar si la empresa y los registros de correo existen
        if ($empresa && $empresa->registrosCorreo) {
            $this->verRegistrosAsociados = $empresa->registrosCorreo->pluck('FECHA')->toArray();
        } else {
            $this->verRegistrosAsociados = [];
        }

        $this->dispatchBrowserEvent('show-view-correo-modal');
    }

    public $verRuc, $verRazonSocial, $verCorreo, $verTelefono, 
    $verDepartamento, $verProvincia, $verDistrito, $verDireccion, 
    $verReferencia, $verTipoEmpresa, $verLocali, $verRpL, $datosEmpresa = [];

    public function viewDetalle($id)
    {
        
        $empresa = Empresa::with(['empresaDato'])->where('RUC_EMPLEADOR', strval($id))->first();
        $representante = EmpresaRpL::where('RUC_EMPLEADOR', strval($id))->first();
        $this->verRuc = $empresa->RUC_EMPLEADOR;
        $this->verRazonSocial = $empresa->RAZON_SOCIAL ?? 'No disponible';
        $this->verRpL = $representante->REPRESENTANTE_LEGAL ?? 'No disponible';
        $this->verCorreo = $representante->RL_CORREO ?? 'No disponible';
        $this->verTelefono = $representante->RL_TELEFONO ?? 'No disponible';
        $this->verDepartamento = $empresa->Departamento->DEPARTAMENTO ?? 'No disponible';
        $this->verProvincia = $empresa->Provincia->PROVINCIA ?? 'No disponible';
        $this->verDistrito = $empresa->Distrito->DISTRITO ?? 'No disponible';
        $this->verDireccion = $empresa->DIRECC ?? 'No disponible';
        $this->verReferencia = $empresa->REFERENCIA ?? 'No disponible';
        $this->verTipoEmpresa = $empresa->TipoEmpresa->NOMBRE_TIPO ?? 'No disponible';
        $this->dispatchBrowserEvent('show-view-detalle-modal');

        $this->datosEmpresa = $empresa->empresaDato->map(function ($dato) {
            return [
                'TELEFONO' => $dato->TELEFONO, // Adjust these keys based on actual EmpresaDato attributes
                'CORREO' => $dato->CORREO,
            ];
        });
        
    }

    public $loading = false;


    public function enviarCorreo () {
        try {
            $this->loading = true;
            $empresasSeleccionadas = Empresa::whereIn('RUC_EMPLEADOR', $this->rucSeleccionados)->get();
            foreach ($empresasSeleccionadas as $empresa) {
                $representante = EmpresaRpL::where('RUC_EMPLEADOR', $empresa->RUC_EMPLEADOR)->first();
                $destinatario = $representante->RL_CORREO;
                $asunto = $this->correoAsunto;
                $mensaje = $this->correoMensaje;
                $firma = $this->correoFirma;

                $asunto = str_replace('[RUC_EMPLEADOR]', $empresa->RUC_EMPLEADOR, $asunto);
                $asunto = str_replace('[RAZON_SOCIAL]', $empresa->RAZON_SOCIAL, $asunto);
                $mensaje = str_replace('[RAZON_SOCIAL]', $empresa->RAZON_SOCIAL, $mensaje);
                $asunto = str_replace('[DEPARTAMENTO]', $empresa->Departamento->DEPARTAMENTO, $asunto);
                    
                if($this->enviarWhatsapp) {
                    SendMessage::message($representante->RL_TELEFONO)->attachText($mensaje)->send();
                }

                if($destinatario != null) {
                    $usuario = auth()->user();
    
                    config(['mail.from.address' => $usuario->email]);
                    config(['mail.from.name' => $usuario->email]);
                    config(['mail.from.password' => $usuario->password]); 

                    Mail::to($destinatario)->send(new Correo($asunto, $mensaje, $firma));
        
                    $registroCorreo = new RegistroCorreo();
                    $registroCorreo->RUC_EMPLEADOR = $empresa->RUC_EMPLEADOR;;
                    $registroCorreo->FECHA = now(); // Laravel proporciona una forma más limpia de obtener la fecha actual
                    $registroCorreo->save();
                }

                
            }
            session()->flash('success', 'Correo enviado correctamente.');

            config(['mail.from.address' => env('MAIL_FROM_ADDRESS')]);
            config(['mail.from.name' => env('MAIL_FROM_NAME')]);
            config(['mail.from.password' => env('MAIL_FROM_PASSWORD')]);

            $this->dispatchBrowserEvent('cerrarModal');
            
            return $this->descargarCorreosNoEnviados();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar el correo: ' . $e->getMessage());
            $this->dispatchBrowserEvent('cerrarModal');
        } finally {
            $this->loading = false; // Desactivar el estado de carga
        }
    }
    
    public function descargarCorreosNoEnviados()
    {
        $empresasSeleccionadas = Empresa::whereIn('RUC_EMPLEADOR', $this->rucSeleccionados)->get();
        $correosNoEnviados = [];
        foreach ($empresasSeleccionadas as $empresa) {
            $destinatario = $empresa->CORREO;
            if($destinatario == null) {
                $correosNoEnviados[] = $empresa->RUC_EMPLEADOR . ' - ' . $empresa->RAZON_SOCIAL;
            }
        }
        if(count($correosNoEnviados) > 0) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $text = $section->addText('Empresas que no tienen correos');
            foreach ($correosNoEnviados as $correos) {
                $text = $section->addText($correos);
            }
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = 'empresas_sin_correo.docx';
            $objWriter->save($fileName);
            return response()->download(public_path($fileName))->deleteFileAfterSend(true);
        }else {
            return null;
        }
    }

    public function importar ()
    {
        try {
            $this->loading = true;
            $this->validate([
                'excel' => 'required|mimes:xlsx,xls',
            ]);
            $file = $this->excel->getRealPath();
            Excel::import(new EmpresaImport, $file);   
            session()->flash('success', 'Datos actualizados correctamente.'); 
            $this->excel = null;
            $this->dispatchBrowserEvent('close-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar los datos: ' . $e->getMessage());
            $this->excel = null;
            $this->dispatchBrowserEvent('close-modal');
        } finally {
            $this->loading = false; // Desactivar el estado de carga
        }
    }

    public $tipoAFP;

    public function mount()
    {
        $this->tipoAFP = '[TIPO_AFP]'; // Asegúrate de inicializar $tipoAFP
        $this->correoFirma = auth()->user()->name . "\nEJECUTIVO DE COBRANZA - AREA LEGAL\nFERNÁNDEZ NUÑEZ ASOCIADOS SRL\n\n[1] De conformidad con lo regulado por el artículo 35° del D.S. 054-97-EF (TUO de la Ley del Sistema Privado de Administración de Fondos de Pensiones). \n[2] Documentos idóneos que se encuentran regulados en el artículo 38° D.S. 054-97-EF (TUO de la Ley del Sistema Privado de Administración de Fondos de Pensiones).";
        $this->actualizarMensaje($this->tipoAFP);
    }

    public function updatedTipoAFP($value)
    {
        $this->actualizarMensaje($value);
    }

    public function actualizarMensaje($tipo)
    {
        $this->correoAsunto = str_replace('[TIPO_AFP]', $tipo, "COBRANZA JUDICIAL [TIPO_AFP] AFP - RUC [RUC_EMPLEADOR] - [RAZON_SOCIAL] - [DEPARTAMENTO]");
        $this->correoMensaje = str_replace('[TIPO_AFP]', $tipo, "Estimados Señores: [RAZON_SOCIAL] \n\nDe nuestra mayor consideración \n\nMediante la presente, lo saludamos en representación de [TIPO_AFP] AFP, la misma que nos ha encomendado la cobranza judicial de la deuda por aportes previsionales descontados a sus trabajadores, pero no pagados en su oportunidad. \n\n"
        . "El incumplimiento del pago, y en su defecto la no presentación de la Declaración Sin Pago o la formulación incompleta de la misma, por cualquier período o devengue podría generarle una eventual sanción multa por SUNAFIL equivalente al 10% de la UIT vigente por cada trabajador no declarado[1]. Asimismo, la AFP se encuentra facultada a reportar vuestras deudas a las Centrales de Riesgo. \n\n"
        . "Al margen de las consecuencias que podría asumir ante SUNAFIL, su deuda por aportes previsionales impagos de sus trabajadores afiliados a [TIPO_AFP] AFP, ya se encuentra en cobranza judicial. Tales aportes debieron incrementar sus cuentas de capitalización para sus futuras pensiones de jubilación; sin embargo, la falta de pago los coloca en estado de desprotección latente para acceder a la cobertura de seguro de invalidez, sobrevivencia para sus familiares y gastos de sepelio. \n\n"
        . "Considerando estos antecedentes, su representada debe regularizar en las próximas 48 horas, el pago de los aportes previsionales impagos, intereses y los gastos judiciales y/o sírvase contactarnos a través de los datos de contacto que indicamos en la parte inferior de la presente. \n\n"
        . "De existir alguna observación sobre la deuda requerida, sírvase comunicarse al celular 962362625 y/o LINK https://wa.me/message/3VY42SC5VCXJD1 y al correo, apacheco@fernandezasociados.pe las respectivas copias de las Planillas de Haberes o Boletas de Pago recibidas por sus trabajadores, Planillas de Aportes Previsionales debidamente canceladas y Liquidaciones de Compensación por Tiempo de Servicio[2]. \n\n"
        . "Finalmente, señalamos que [TIPO_AFP] AFP está facultada dentro de las atribuciones que le otorga la Ley, a interponer medidas cautelares y/o embargos, inclusive denuncias penales contra su representada. \n\n"
        . "Sin otro particular, quedamos a la espera de su respuesta. \n\n"
        . "Atentamente,");
    }

}
