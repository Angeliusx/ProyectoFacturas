<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\DemandaProfuturo;
use App\Models\Empresa;
use App\Models\Evento;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Sinoe;
use App\Models\EventoDemandaProfuturo;
use DateTime;



class DemandaProfuturoImport implements ToModel, WithHeadingRow
{
    private static $demandasNoImportadas = [];

    public function model(array $row)
    {

        if (!isset($row['ruc_empleador'])) {
            return null; // O manejar el caso en que no haya 'ruc_empleador'
        }

        $IdsLetras = [
            'A' => "Ana Pacheco",
            'X' => "Ximena Zavaleta",
            'F' => "Fátima Rodríguez",
            'D' => "Deiner Diaz",
            'C' => "Cristhofer Fernández",
            'L' => "Lesslie Calle",
            'E' => "Edita Campos",
            'ED' => "Edgar Pacheco",
        ];


        $fecha_presentacion = null;

        $columnasRepetidas = ['codigo_evento'];
        
        $empresa = Empresa::where('RUC_EMPLEADOR', $row['ruc_empleador'])->first();
        $demandaprofuturo = DemandaProfuturo::where('NUM_DEMANDA', $row['num_demanda'])->first();
        $sinoe = Sinoe::where('NOMBRE_SINOE', $row['sinoe'])->first();

        $distrito = $row['distrito'];
        $provincia = $row['provincia'];
        $departamento = $row['departamento'];

        $distritoId = Distrito::where('DISTRITO', $distrito)->value('ID_DIST');
        $provinciaId = Provincia::where('PROVINCIA', $provincia)->value('ID_P');
        $departamentoId = Departamento::where('DEPARTAMENTO', $departamento)->value('ID_D');

        if (!$empresa) {
            $empresa = Empresa::UpdateOrCreate([
                'RUC_EMPLEADOR' => $row['ruc_empleador'],
                'RAZON_SOCIAL' => $row['razon_social'],
                'TIPO_EMPRESA' => $row['tipo_empresa'],
                'DIRECC' => isset($row['direcc']) ? $row['direcc'] : null,
                'LOCALI' => isset($row['locali']) ? $row['locali'] : null,
                'REFERENCIA' => isset($row['referencia']) ? $row['referencia'] : null,
                'DISTRITO' => $distritoId,
                'PROVINCIA' => $provinciaId,
                'DEPARTAMENTO' => $departamentoId,
                'TELEFONO' => isset($row['telefono']) ? $row['telefono'] : null,
            ]);
        }

        $fe_emision = DateTime::createFromFormat('d/m/Y', $row['fe_emision']);
        $fecha_evento = DateTime::createFromFormat('d/m/Y', $row['fecha_evento_1']);
        if ($row['codigo_evento_1'] == 100) {
           $fecha_presentacion =  $fecha_evento;
        }
        
        $dividir = explode("; ", $row['num_demanda']);

        foreach ($dividir as $demanda) {
            if (!empty($demanda)) {
                $demandaProfuturo = DemandaProfuturo::where('NUM_DEMANDA', $demanda)->first();
                if ($demandaProfuturo) {
                    return null;
                }
                if ($fe_emision instanceof DateTime) {
                    $demandaProfuturo = new DemandaProfuturo([
                        'NUM_DEMANDA' => $demanda,
                        'RUC_EMPLEADOR' => $row['ruc_empleador'],
                        'FE_EMISION' => $fe_emision,
                        'COD_ESTUDIO' => $row['cod_estudio'],
                        'NOMBRE_EST' => $row['eeaa'],
                        'CODIGO_UNICO_EXPEDIENTE' => $row['codigo_unico_expediente'],
                        'FECHA_PRESENTACION' => $fecha_presentacion,
                        'NRO_EXPEDIENTE' => $row['nro_expediente'],
                        'AÑO' => $row['ano'],
                        'SECRETARIO' => $row['secretario'],
                        'JUZGADO' => $row['juzgado'],
                        'DESCRIPCION_JUZGADO' => $row['descripcion_juzgado'],
                        'TOTAL_DEMANDADO' => $row['total_demandado'],
                        'TIPO_DEUDA' => $row['tipo_deuda'],
                        'ID_ESTADO' => 1,
                        'ID_SINOE' => $sinoe ? $sinoe->ID_SINOE : null, 
                    ]);
                } else {
                    return null;
                }
                
    
                $demandaProfuturo->save();
    
                foreach ($columnasRepetidas as $columna) {
                    $indice = 1;
                    while (isset($row[$columna . '_' . $indice])) {
                        if (!empty($row['codigo_evento_' . $indice])) {
                            $eventoDemandaProfuturo = new EventoDemandaProfuturo([
                                'ID_DEMANDAPRO' => $demandaProfuturo->ID_DEMANDAPRO,
                                'CODIGO_EVENTO' => $row['codigo_evento_' . $indice],
                                'RESOLUCION' => $row['resolucion_' . $indice] ?? "0",
                                'FECHA_EVENTO' => DateTime::createFromFormat('d/m/Y', $row['fecha_evento_' . $indice]),
                                'ID_REGISTRO' => 1,
                                'OBSERVACIONES' => $row['observacion_' . $indice] ?? NULL,
                            ]);
    
                            $eventoDemandaProfuturo->save();
                        }
                        $indice++;
                    }
                }
                if (isset($row['actividad'])) {
                    $actividadExistente = Actividad::where('ID_DEMANDAPRO', $demandaProfuturo->ID_DEMANDAPRO)->first();
    
                    if ($actividadExistente) {
                        $actividadExistente->delete();
                    }
       
                    $id_usuario = isset($IdsLetras[$row['actividad']] ) ? $IdsLetras[$row['actividad']] : null;
    
                    if ($id_usuario !== null) {
                        $id = User::where('name', $id_usuario)->value('id');
                        $actividad = new Actividad();
                        $actividad->ID_DEMANDAPRO = $demandaProfuturo->ID_DEMANDAPRO;
                        $actividad->ID_USUARIO = $id;
                        $actividad->ACTIVIDAD = ' Realizo a la Demanda Profuturo ' . $demandaProfuturo->NUM_DEMANDA;
                        $actividad->save();
                    }
                }
            }
            else {
                return null;
            }
        }
        
        return $demandaProfuturo;
    }
}
