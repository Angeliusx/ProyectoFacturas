<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\DemandaPrima;
use App\Models\Empresa;
use App\Models\Estudio;
use App\Models\Evento;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Actividad;
use App\Models\Sinoe;
use App\Models\EventoDemandaPrima;
use App\Models\EmpresaDato;
use App\Models\EmpresaRpL;
use App\Models\User;
use DateTime;



class EmpresaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $columnasRepetidas = ['telefono'];
 
        $ruc=(string)($row['ruc_empleador']);
        $distrito = $row['distrito'];
        $provincia = $row['provincia'];
        $departamento = $row['departamento'];

        $distritoId = Distrito::where('DISTRITO', $distrito)->value('ID_DIST');
        $provinciaId = Provincia::where('PROVINCIA', $provincia)->value('ID_P');
        $departamentoId = Departamento::where('DEPARTAMENTO', $departamento)->value('ID_D');

        $empresa = Empresa::where('RUC_EMPLEADOR', $ruc)->first();

        

        if ($empresa) {
            $empresa->update([
                'RAZON_SOCIAL' => $row['razon_social'],
                'TIPO_EMPRESA' => $row['tipo_empresa'],
                'DIRECC' => $row['direccion'] ? $row['direccion'] : null,
                'LOCALI' => $row['locali'] ? $row['locali'] : null,
                'REFERENCIA' => $row['referencia'] ? $row['referencia'] : null,
                'DISTRITO' => $distritoId,
                'PROVINCIA' => $provinciaId,
                'DEPARTAMENTO' => $departamentoId,
            ]);

            $representante = EmpresaRpL::where('RUC_EMPLEADOR', $ruc)->first();
            
            // Crear o actualizar el representante legal
            $rl_telefono = $row['rl_telefono'] ?? null;
            if (strlen($rl_telefono) <= 9) {
                // Crear o actualizar el representante legal
                if ($representante) {
                    $representante->update([
                        'REPRESENTANTE_LEGAL' => $row['representante_legal'],
                        'RL_CORREO' => $row['rl_correo'],
                        'RL_TELEFONO' => $rl_telefono,
                    ]);
                } else {
                    EmpresaRpL::create([
                        'RUC_EMPLEADOR' => $ruc,
                        'REPRESENTANTE_LEGAL' => $row['representante_legal'],
                        'RL_CORREO' => $row['rl_correo'],
                        'RL_TELEFONO' => $rl_telefono,
                    ]);
                }
            }

            foreach ($columnasRepetidas as $columna) {
                $indice = 1;
                $telefonosGuardados = EmpresaDato::where('RUC_EMPLEADOR', $ruc)->pluck('TELEFONO')->toArray();
                $correosGuardados = EmpresaDato::where('RUC_EMPLEADOR', $ruc)->pluck('CORREO')->toArray();
                
                while (isset($row[$columna . '_' . $indice])) {
                    $telefono = $row['telefono_' . $indice] ?? null;
                    $correo = $row['correo_' . $indice] ?? null;
                    
                    if (strlen($telefono) <= 9 && !in_array($telefono, $telefonosGuardados)) {
                        $empresadato = new EmpresaDato([
                            'RUC_EMPLEADOR' => $ruc,
                            'TELEFONO' => $telefono,
                        ]);
                        
                        $empresadato->save();
                        $telefonosGuardados[] = $telefono;
                    } 
                    if (!in_array($correo, $correosGuardados)) {
                        $empresadato = new EmpresaDato([
                            'RUC_EMPLEADOR' => $ruc,
                            'CORREO' => $correo,
                        ]);
                        
                        $empresadato->save();
                        $correosGuardados[] = $correo;
                    }
                    
                    $indice++;
                }
            }
            
    
            
        } else {
            $empresa = new Empresa([
                'RUC_EMPLEADOR' => $ruc,
                'RAZON_SOCIAL' => $row['razon_social'],
                'TIPO_EMPRESA' => $row['tipo_empresa'],
                'DIRECC' => $row['direccion'],
                'LOCALI' => $row['locali'],
                'REFERENCIA' => $row['referencia'],
                'DISTRITO' => $distritoId,
                'PROVINCIA' => $provinciaId,
                'DEPARTAMENTO' => $departamentoId,
            ]);
            $empresa->save();

            $representante = EmpresaRpL::where('RUC_EMPLEADOR', $ruc)->first();
            
            // Crear o actualizar el representante legal
            $rl_telefono = $row['rl_telefono'] ?? null;
            if (strlen($rl_telefono) <= 9) {
                // Crear o actualizar el representante legal
                if ($representante) {
                    $representante->update([
                        'REPRESENTANTE_LEGAL' => $row['representante_legal'],
                        'RL_CORREO' => $row['rl_correo'],
                        'RL_TELEFONO' => $rl_telefono,
                    ]);
                } else {
                    EmpresaRpL::create([
                        'RUC_EMPLEADOR' => $ruc,
                        'REPRESENTANTE_LEGAL' => $row['representante_legal'],
                        'RL_CORREO' => $row['rl_correo'],
                        'RL_TELEFONO' => $rl_telefono,
                    ]);
                }
            }

            foreach ($columnasRepetidas as $columna) {
                $indice = 1;
                $telefonosGuardados = [];
                $correosGuardados = [];
                
                while (isset($row[$columna . '_' . $indice])) {
                    $telefono = $row['telefono_' . $indice] ?? null;
                    $correo = $row['correo_' . $indice] ?? null;
                    
                    if (strlen($telefono) <= 9 && !in_array($telefono, $telefonosGuardados)) {
                        $empresadato = new EmpresaDato([
                            'RUC_EMPLEADOR' => $ruc,
                            'TELEFONO' => $telefono,
                            'CORREO' => $correo,
                        ]);
                        
                        $empresadato->save();
                        $telefonosGuardados[] = $telefono;
                    } elseif (!in_array($correo, $correosGuardados)) {
                        $empresadato = new EmpresaDato([
                            'RUC_EMPLEADOR' => $ruc,
                            'TELEFONO' => $telefono,
                            'CORREO' => $correo,
                        ]);
                        
                        $empresadato->save();
                        $correosGuardados[] = $correo;
                    }
                    
                    $indice++;
                }
            }
            
    
            
        }
    
    }
}
