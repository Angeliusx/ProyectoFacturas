<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DemandaPrimaDeuda;
use App\Models\DemandaPrima;
use App\Models\Evento;
use App\Models\Deuda;
use App\Models\Empresa;
use App\Models\Estudio;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\EventoDemandaPrima;
use Illuminate\Support\Collection;

class DemandaPrimaExport implements FromCollection, WithHeadings
{
    private $data;
    private $repetirevento;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {

        return $this->data->map(function ($demanda) {
            $eventos = $demanda->evento->sortBy(function ($evento) {
                return $evento->pivot->FECHA_EVENTO; // Ordenar por la fecha en la tabla pivote
            })->map(function ($evento) {
                return [
                    'RESOLUCION' => $evento->pivot->RESOLUCION,
                    'CODIGO_EVENTO' => $evento->CODIGO_EVENTO,
                    'DESCRIPCION_EVENTO' => $evento->DESCRIPCION_EVENTO,
                    'FECHA_EVENTO' => $evento->pivot->FECHA_EVENTO,
                    'OBSERVACIONES' => $evento->pivot->OBSERVACIONES ?? '',
                ];
            });
    
            $eventosFlattened = $eventos->flatMap(function ($evento) {
                return array_values($evento);
            })->toArray();

            $tipDeuda = DemandaPrimaDeuda::where('ID_DEMANDAP', $demanda->ID_DEMANDAP)->get();
            $tipDeudaString = '';
            foreach ($tipDeuda as $key => $value) {
                $deuda = Deuda::where('TIP_DEUDA', $value->TIP_DEUDA)->first();
                $tipDeudaString .= $deuda->TIP_DEUDA . ', ';
            }
            $tipDeudaString = substr($tipDeudaString, 0, -2);
            

            $demandaData = [
                'NR_DEMANDA' => $demanda->NR_DEMANDA,
                'FE_EMISION' => $demanda->FE_EMISION ? $demanda->FE_EMISION : '',
                'RUC_EMPLEADOR' => $demanda->empresa->RUC_EMPLEADOR,
                'RAZON_SOCIAL' => $demanda->empresa->RAZON_SOCIAL,
                'TIPO_EMPRESA' => $demanda->empresa->tipoempresa->NOMBRE_TIPO ?? '',
                'DIRECC' => $demanda->empresa->DIRECC ? $demanda->empresa->DIRECC : '',
                'LOCALI' => $demanda->empresa->LOCALI ? $demanda->empresa->LOCALI : '',
                'REFERENCIA' => $demanda->empresa->REFERENCIA ? $demanda->empresa->REFERENCIA : '',
                'DISTRITO' => $demanda->empresa->distrito ? $demanda->empresa->distrito->DISTRITO : '',
                'PROVINCIA' => $demanda->empresa->provincia ? $demanda->empresa->provincia->PROVINCIA : '',
                'DEPARTAMENTO' => $demanda->empresa->departamento ? $demanda->empresa->departamento->DEPARTAMENTO : '',
                'TELEFONO' => $demanda->empresa->TELEFONO ? $demanda->empresa->TELEFONO : '',
                'COD_ESTUDIO' => $demanda->COD_ESTUDIO ? $demanda->COD_ESTUDIO : '',
                'NOMBRE_EST' => $demanda->estudio->NOMBRE_EST ? $demanda->estudio->NOMBRE_EST : '',
                'MTO_TOTAL_DEMANDA' => $demanda->MTO_TOTAL_DEMANDA ? $demanda->MTO_TOTAL_DEMANDA : '',
                'TIP_DEUDA' => $tipDeudaString ? $tipDeudaString : '',
                'CODIGO_UNICO_EXPEDIENTE' => $demanda->CODIGO_UNICO_EXPEDIENTE ? $demanda->CODIGO_UNICO_EXPEDIENTE : '',
                'EXPEDIENTE' => $demanda->EXPEDIENTE ? $demanda->EXPEDIENTE : '',
                'AÑO' => $demanda->AÑO ? $demanda->AÑO : '',
                'SECRETARIO_JUZGADO' => $demanda->juzgado?->secretariojuzgado?->SECRETARIO_JUZGADO ?? '',
                'CODIGO_JUZGADO' => $demanda->juzgado?->CODIGO_JUZGADO ?? '',
                'DESCRIPCION_JUZGADO' => $demanda->juzgado?->descripcionjuzgado?->DESCRIPCION_JUZGADO ?? '',
            ];

            return array_merge($demandaData, $eventosFlattened);
        });
    }

    public function headings(): array
    {
        return [
            'NR_DEMANDA',
            'FE_EMISION',
            'RUC_EMPLEADOR',
            'RAZON_SOCIAL',
            'TIPO_EMPRESA',
            'DIRECC',
            'LOCALI',
            'REFERENCIA',
            'DISTRITO',
            'PROVINCIA',
            'DEPARTAMENTO',
            'TELEFONO',
            'COD_ESTUDIO',
            'NOMBRE_EST',
            'MTO_TOTAL_DEMANDA',
            'TIP_DEUDA',
            'CODIGO_UNICO_EXPEDIENTE',
            'EXPEDIENTE',
            'AÑO',
            'SECRETARIO_JUZGADO',
            'CODIGO_JUZGADO',
            'DESCRIPCION_JUZGADO',
            'RESOLUCION',
            'CODIGO_EVENTO',
            'DESCRIPCION_EVENTO',
            'FECHA_EVENTO',
            'OBSERVACIONES',
        ];
    }
}

