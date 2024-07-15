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


class Facturas extends Component
{
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
        

        return view('livewire.pages.facturas', [
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
