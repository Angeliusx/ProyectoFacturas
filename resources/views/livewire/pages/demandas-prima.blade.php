<div>
    @include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>DEMANDA PRIMA</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Actividad de la Demanda Prima</h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="campoSeleccionado">Campo:</label>
                        <select id="campoSeleccionado" wire:model="campoSeleccionado" class="form-control">
                            @foreach ($camposBusqueda as $campo => $label)
                                <option value="{{ $campo }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="busqueda">Buscar:</label>
                        <input id="busqueda" wire:model="busqueda" type="text" class="form-control" placeholder="Buscar...">
                    </div>
                </div>

                <div class="col-md-6">
                    @if (auth()->user()->hasRole('admin'))
                        <div class="form-group">
                            <label>Importar</label>
                            <br>
                            <div class="input-group">
                            <input type="file" class="form-control" wire:model="excel" id="excel" class="form-control-file" wire:loading.attr="disabled" wire:target="excel" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                            @if ($excel != null)
                                <button class="btn btn-outline-primary" type="button" id="excel" wire:click="importar" wire:loading.attr="disabled" wire:target="excel">Importar</button>
                            @endif
                            </div>

                            @if (session()->has('success'))
                                <div class="mt-3">
                                    <button class="btn btn-outline-danger" wire:click="logs">Demandas no importadas</button>
                                </div>
                            @endif


                            <div wire:loading wire:target="importar">
                                <div class="overlay">
                                <div class="loader">
                                    <svg viewBox="0 0 80 80">
                                        <circle id="test" cx="40" cy="40" r="32"></circle>
                                    </svg>
                                </div>
                                <div class="loader triangle">
                                    <svg viewBox="0 0 86 80">
                                        <polygon points="43 8 79 72 7 72"></polygon>
                                    </svg>
                                </div>
                                <div class="loader">
                                    <svg viewBox="0 0 80 80">
                                        <rect x="8" y="8" width="64" height="64"></rect>
                                    </svg>
                                </div>
                                </div>
                            </div>
                        </div>
                    @endif
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="filtroAnos">Filtrar por Año:</label>
                                <select id="filtroAnos" wire:model="filtroAnos" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($años as $año)
                                        <option value="{{ $año }}">{{ $año }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filtroEvento">Filtrar por Evento:</label>
                                <select id="filtroEvento" wire:model="filtroEvento" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento->CODIGO_EVENTO }}">{{ $evento->CODIGO_EVENTO }} - {{ $evento->DESCRIPCION_EVENTO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                    
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col">Nro Demanda</th>
                            <th scope="col">RUC</th>
                            <th scope="col">Razon Social</th>
                            <th scope="col">Codigo de Expediente</th>
                            <th scope="col" width="10%">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($demandaprima->isEmpty())
                            <tr>
                                <td colspan="5">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($demandaprima as $demandap)
                                <tr>
                                    <th>{{ ($demandaprima->currentpage() - 1) * $demandaprima->perpage() + $loop->index + 1 }}</th>
                                    <td>{{ $demandap->NR_DEMANDA }}</td>
                                    <td>{{ $demandap->Empresa->RUC_EMPLEADOR }}</td>
                                    <td>{{ $demandap->Empresa->RAZON_SOCIAL }}</td>
                                    <td>{{ $demandap->CODIGO_UNICO_EXPEDIENTE ?? 'NA' }}</td>
                                    <td>
                                        <div class="d-flex">
                                        <!-- foreach (navigation_links as link) -->
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Ver" wire:click="viewDemandaDetails({{ $demandap->ID_DEMANDAP }})">
                                                <i class="fas fa-eye text-primary"></i>
                                            </button>
                                            @if (auth()->user()->hasRole('admin'))
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Agregar Cod" wire:click="addEventoView({{ $demandap->ID_DEMANDAP }})">
                                                <i  class="fas fa-plus text-success"></i>
                                            </button>
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Editar" wire:click="editDemanda({{ $demandap->ID_DEMANDAP }})">
                                                <i  class="fas fa-edit text-info"></i>
                                            </button>
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Eliminar" wire:click="deleteConfirmation({{ $demandap->ID_DEMANDAP }})">
                                                <i  class="fas fa-trash text-danger"></i>
                                            </button>
                                            @elseif (auth()->user()->hasRole('user'))
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Editar" wire:click="editDemanda({{ $demandap->ID_DEMANDAP }})">
                                                <i  class="fas fa-edit text-info"></i>
                                            </button>
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                                data-placement="top" title="Agregar Cod" wire:click="addEventoView({{ $demandap->ID_DEMANDAP }})">
                                                <i class="fas fa-plus text-success"></i>
                                            </button>
                                            @endif
                                        <!-- endforeach -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $demandaprima->links() }}
            </div>    
        </div>
        <div class="card-footer">
                <div data-tooltip="Exportar" class="button btn-primary" data-toggle="modal" data-target="#ExportModal">
                    <div class="button-wrapper">
                        <div class="text">Exportar</div>
                        <span class="icon">
                        <svg viewBox="0 0 600 512" class="bi bi-cart2" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V288H216c-13.3 0-24 10.7-24 24s10.7 24 24 24H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zM384 336V288H494.1l-39-39c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l80 80c9.4 9.4 9.4 24.6 0 33.9l-80 80c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l39-39H384zm0-208H256V0L384 128z"/></path>
                        </svg>
                        </span>
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal Detalles-->

    <div wire:ignore.self class="modal fade" id="viewDemandaModal" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Demanda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="details-section">
                                <p><strong>Nro Demanda:</strong> {{$nr_demanda}} <br>
                                @if ($descripcion_estado == 1)
                                    <span class="badge badge-success">VIGENTE</span>
                                @else
                                    <span class="badge badge-danger">CERRADA</span>
                                @endif
                                @if ($repro == "TOTAL")
                                    <span class="badge badge-warning">TOTAL</span>
                                @elseif ($repro == "PARCIAL")
                                    <span class="badge badge-danger">PARCIAL</span>
                                @else
                                    <span class="badge badge-info">NO REPRO</span>
                                @endif
                                </p>
                                <p><strong>RUC:</strong> {{$ruc_empleador}} - {{$nombre_tipo}}</p>
                                <p><strong>Razón Social:</strong> {{$razon_social}}</p>
                                <p><strong>Código Único de Expediente:</strong> {{$codigo_unico_expediente}}</p>
                                <p><strong>Monto Total de la Demanda:</strong> {{$mto_total_demanda}}</p>
                                <p><strong>Deuda:</strong>
                                <p><strong>Deuda:</strong>
                                    @php
                                        $tieneReal = false;
                                        $tienePresunta = false;
                                        foreach ($deudaTotal as $deuda) {
                                            if ($deuda['sub_deuda'] == 'REAL') {
                                                $tieneReal = true;
                                            }
                                            if ($deuda['sub_deuda'] == 'PRESUNTA') {
                                                $tienePresunta = true;
                                            }
                                        }
                                    @endphp

                                    @if ($tieneReal && $tienePresunta)
                                        MIXTA (
                                        @foreach ($deudaTotal as $deuda)
                                            <span title="{{ $deuda['descripcion_deuda'] }}">{{ $deuda['tip_deuda'] }}</span>@if (!$loop->last), @endif
                                        @endforeach
                                        )
                                    @else
                                        @foreach ($deudaTotal as $deuda)
                                            <span title="{{ $deuda['descripcion_deuda'] }}">{{ $deuda['tip_deuda'] }} - {{ $deuda['descripcion_deuda'] }}</span>@if (!$loop->last) / @endif
                                        @endforeach
                                    @endif
                                </p>
                                <p><strong>Fecha de Emisión:</strong> {{$fe_emision}}</p>
                                <p><strong>Fecha de Presentación:</strong> {{$fecha_presentacion}}</p>
                                <p><strong>Código de Estudio:</strong> {{$cod_estudio}} - {{$nombre_estudio}}</p>
                                <p><strong>Secretario:</strong> {{$secretario_juzgado}}</p>
                                <p><strong>Juzgado:</strong> {{$codigo_juzgado}} - {{$descripcion_juzgado}}</p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            @if(isset($eventoTotal) && count($eventoTotal) > 0)
                                <div class="events-section">
                                    <p><strong>Eventos Asociados:</strong></p>
                                    <ul class="list-group">
                                        @foreach($eventoTotal as $evento)
                                        <li class="list-group-item">
                                            Res {{$evento['resolucion_evento']}} / 
                                            @if ($evento['codigo_evento'] != 0)
                                                {{$evento['codigo_evento']}} - {{$evento['nombre_evento']}} - {{$evento['fecha_evento']}}
                                                @if ($evento['observacion_evento'] != 'NA')
                                                    -
                                                        {{$evento['observacion_evento']}}
                                                    -
                                                @endif
                                            @else
                                                {{$evento['observacion_evento']}} - {{$evento['fecha_evento']}}
                                            @endif
                                            <button class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top"
                                                title="{{$evento['registro']}}{{ ($evento['registro'] == 'CEJ') ? ' / '.$evento['ubiproceso'] : '' }}">
                                                <i class="fas fa-eye text-primary"></i>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p><em>No hay eventos asociados.</em></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button class="Download-button" wire:click="descargarInfo({{$this->idprima}})">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            height="16"
                            width="20"
                            viewBox="0 0 640 512"
                        >
                            <path
                            d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-167l80 80c9.4 9.4 24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-39 39V184c0-13.3-10.7-24-24-24s-24 10.7-24 24V318.1l-39-39c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9z"
                            fill="white"
                            ></path>
                        </svg>
                        <span>Descargar</span>
                    </button>
                    <button class="btnSalir"  data-dismiss="modal"> Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Modal Add Evento-->
    <div wire:ignore.self class="modal fade" id="agregarEventoModal" tabindex="-1" role="dialog" aria-labelledby="agregarEventoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="agregarEventoModalLabel">Agregar Nuevo Evento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <ul class="list-group">
                            @foreach($verEventosAsociados as $evento)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                    @if ($evento['codigo_evento'] != 0)
                                        <strong>Evento:</strong> {{$evento['resolucion_evento']}} / {{$evento['codigo_evento']}} - {{$evento['nombre_evento']}}
                                        <br>
                                        <strong>Fecha:</strong> {{$evento['fecha_evento']}}
                                        @isset($evento['observacion_evento'])
                                            <br>
                                            <strong>Observacion:</strong> {{$evento['observacion_evento']}}
                                        @endisset
                                    @else
                                        <strong>Evento:</strong> {{$evento['resolucion_evento']}} / {{$evento['observacion_evento']}}
                                        <br>
                                        <strong>Fecha:</strong> {{$evento['fecha_evento']}}
                                    @endif

                                    <button class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="{{$evento['registro']}}{{ ($evento['registro'] == 'CEJ') ? ' / '.$evento['ubiproceso'] : '' }}">
                                        <i class="fas fa-eye text-primary"></i>
                                    </button>
                                        
                                    </div>
                                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('user'))
                                    <div>
                                        <button class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top"
                                                title="Delete" wire:click="eliminarEvento({{$evento['codigo_evento']}},'{{$evento['observacion_evento']}}')">
                                                <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <br>
                        <x-auth-validation-errors class="mb-4" :errors="$errors" userName="{{auth()->user()->name}}" 
                            x-data="{ showError: true }"
                            x-show="showError"
                            x-init="setTimeout(() => { showError = false; }, 10000)"/>
                        <div class="form-group">
                            <label for="resolucion">Resolucion</label>
                            <input id="resolucion" type="text" class="form-control" wire:model='resolucion'>
                        </div>
                        <div class="form-group">
                            <label for="eventoSeleccionado">Selecciona un evento:</label>
                            <select class="form-control" id="codigoEvento" wire:model='codigoEvento'>
                                <option value="">Selecciona un evento</option>
                                @foreach($verEventosDisponibles as $eve)
                                    <option value="{{$eve['codigo']}}">{{$eve['codigo']}} - {{$eve['nombre']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fechaEvento">Fecha del Evento:</label>
                            <input id="fechaEvento" type="date" class="form-control" wire:model='fechaEvento'>
                        </div>
                        <div class="form-group">
                            <x-label for="idRegistro" :value="__('Tipo de Registro')" />
                            <select id="idRegistro" name="idRegistro" class="form-control" wire:model='idRegistro'>
                                <option value="">Selecciona el Registro</option>
                                @foreach ($registro as $r)
                                    <option value="{{ $r->ID_REGISTRO }}">{{ $r->MODO_REGISTRO }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($idRegistro == 2)
                        <div class="form-group">
                            <x-label for="idUbiProceso" :value="__('Ubicacion del Proceso')" />
                            <select id="idUbiProceso" name="idUbiProceso" class="form-control" wire:model='idUbiProceso'>
                                <option value="">Selecciona la Ubicacion</option>
                                @foreach ($ubiproceso as $ubi)
                                    <option value="{{ $ubi->ID_UBIPROCESO }}">{{ $ubi->UBICACION_PROCESO }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <input id="observaciones" type="text" class="form-control" wire:model='observaciones'>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="agregarEventoDemanda()">Guardar Evento</button>
                </div>
            </div>
        </div>
    </div>


    <!--Modal Edit-->
    <div wire:ignore.self class="modal fade" id="editDemandaModal" tabindex="-1" role="dialog" aria-labelledby="editDemandaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editDemandaModalLabel">Editar Demanda</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nr_demanda">Nro Demanda</label>
                                <input id="nr_demanda" type="text" class="form-control" wire:model='nr_demanda' >
                            </div>
                            <div class="form-group">
                                <label for="fe_emision">Fecha de emision</label>
                                <input id="fe_emision" type="date" class="form-control" wire:model='fe_emision'>
                            </div>
                            <div class="form-group">
                                <label for="cod_estudio">Codigo de Estudio</label>
                                <select id="cod_estudio" class="form-control" wire:model='cod_estudio'>
                                    <option value="">Selecciona un Estudio</option>
                                    @foreach ($estudioTotal as $est)
                                        <option value="{{ $est->COD_ESTUDIO }}">{{ $est->COD_ESTUDIO }} - {{ $est->NOMBRE_EST }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="mto_total_demanda">Monto Total de la Demanda</label>
                                <input id="mto_total_demanda" type="text" class="form-control" wire:model='mto_total_demanda'>
                            </div>
                            <div class="form-group">
                                <label for="codigo_unico_expediente">Codigo Unico de Expediente</label>
                                <input id="codigo_unico_expediente" type="text" class="form-control" wire:model='codigo_unico_expediente'>
                            </div>
                            <div class="form-group">
                                <label for="fecha_presentacion">Fecha de Presentacion</label>
                                <input id="fecha_presentacion" type="date" class="form-control" wire:model='fecha_presentacion'>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="año">Año</label>
                                <input id="año" type="text" class="form-control" wire:model='año'>
                            </div>
                            <div class="form-group">
                                <label for="expediente">Expediente</label>
                                <input id="expediente" type="text" class="form-control" wire:model='expediente'>
                            </div>
                            <div class="form-group">
                                <label for="secretario_juzgado">Secretario del Juzgado</label>
                                <input id="secretario_juzgado" type="text" class="form-control" wire:model='secretario_juzgado' list="listaSecretario">
                                <datalist id="listaSecretario">
                                    @foreach ($secretarioTotal as $sec)
                                        <option value="{{$sec->SECRETARIO_JUZGADO}}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_juzgado">Descripcion del Juzgado</label>
                                <input id="descripcion_juzgado" type="text" class="form-control" wire:model='descripcion_juzgado' list="listaJuzgado">
                                <datalist id="listaJuzgado">
                                    @foreach ($descripcionTotal as $des)
                                        <option value="{{$des->DESCRIPCION_JUZGADO}}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="codigo_juzgado">Codigo del Juzgado</label>
                                <input id="codigo_juzgado" type="text" class="form-control" wire:model='codigo_juzgado'>
                            </div>
                            <div class="form-gropu">
                                <label for="estado">Estado</label>
                                <select id="estado" class="form-control" wire:model='estado'>
                                    <option value="">Selecciona un Estado</option>
                                    @foreach ($estadoTotal as $est)
                                        <option value="{{ $est->ID_ESTADO }}">{{ $est->DESCRIPCION_ESTADO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        <button class="btn btn-sm btn-danger" wire:click="updateDemanda()">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    
    
    <!--Modal Detele-->

    <div wire:ignore.self class="modal fade" id="deleteDemandaModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmacion para eliminar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4 pb-4">
                    <h6>Estas seguro de eliminar esta demanda: {{$verNroDemanda}} ?!</h6>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteDemandaData()">Si! Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Exportar -->

    <div wire:ignore.self class="modal fade" id="ExportModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exportar Datos Prima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Se exportará toda la data de las Demandas PrimaAFP</p>
                    <h6>Filtros para Exportar</h6>
                    <div class="form-group
                    ">
                        <label for="filtroAnos">Filtrar por Año:</label>
                        <select id="filtroAnos" wire:model="filtroAnos" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($años as $año)
                                <option value="{{ $año }}">{{ $año }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group
                    ">
                        <label for="filtroEvento">Filtrar por Evento:</label>
                        <select id="filtroEvento" wire:model="filtroEvento" class="form-control">
                            <option value="">Todos</option>
                            @foreach($eventos as $ev)
                                <option value="{{ $ev->CODIGO_EVENTO }}">{{ $ev->CODIGO_EVENTO }} - {{ $ev->DESCRIPCION_EVENTO }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" wire:click="exportar" data-dismiss="modal">Exportar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
  .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      }
  .loader {
    --path: #2f3545;
    --dot: #5628ee;
    --duration: 3s;
    width: 44px;
    height: 44px;
    position: relative;
  }

  .loader:before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    position: absolute;
    display: block;
    background: var(--dot);
    top: 37px;
    left: 19px;
    transform: translate(-18px, -18px);
    animation: dotRect var(--duration) cubic-bezier(0.785, 0.135, 0.15, 0.86) infinite;
  }

  .loader svg {
    display: block;
    width: 100%;
    height: 100%;
  }

  .loader svg rect, .loader svg polygon, .loader svg circle {
    fill: none;
    stroke: var(--path);
    stroke-width: 10px;
    stroke-linejoin: round;
    stroke-linecap: round;
  }

  .loader svg polygon {
    stroke-dasharray: 145 76 145 76;
    stroke-dashoffset: 0;
    animation: pathTriangle var(--duration) cubic-bezier(0.785, 0.135, 0.15, 0.86) infinite;
  }

  .loader svg rect {
    stroke-dasharray: 192 64 192 64;
    stroke-dashoffset: 0;
    animation: pathRect 3s cubic-bezier(0.785, 0.135, 0.15, 0.86) infinite;
  }

  .loader svg circle {
    stroke-dasharray: 150 50 150 50;
    stroke-dashoffset: 75;
    animation: pathCircle var(--duration) cubic-bezier(0.785, 0.135, 0.15, 0.86) infinite;
  }

  .loader.triangle {
    width: 48px;
  }

  .loader.triangle:before {
    left: 21px;
    transform: translate(-10px, -18px);
    animation: dotTriangle var(--duration) cubic-bezier(0.785, 0.135, 0.15, 0.86) infinite;
  }

  @keyframes pathTriangle {
    33% {
      stroke-dashoffset: 74;
    }

    66% {
      stroke-dashoffset: 147;
    }

    100% {
      stroke-dashoffset: 221;
    }
  }

  @keyframes dotTriangle {
    33% {
      transform: translate(0, 0);
    }

    66% {
      transform: translate(10px, -18px);
    }

    100% {
      transform: translate(-10px, -18px);
    }
  }

  @keyframes pathRect {
    25% {
      stroke-dashoffset: 64;
    }

    50% {
      stroke-dashoffset: 128;
    }

    75% {
      stroke-dashoffset: 192;
    }

    100% {
      stroke-dashoffset: 256;
    }
  }

  @keyframes dotRect {
    25% {
      transform: translate(0, 0);
    }

    50% {
      transform: translate(18px, -18px);
    }

    75% {
      transform: translate(0, -36px);
    }

    100% {
      transform: translate(-18px, -18px);
    }
  }

  @keyframes pathCircle {
    25% {
      stroke-dashoffset: 125;
    }

    50% {
      stroke-dashoffset: 175;
    }

    75% {
      stroke-dashoffset: 225;
    }

    100% {
      stroke-dashoffset: 275;
    }
  }

  .loader {
    display: inline-block;
    margin: 0 16px;
  }
</style>

@push('scripts')
    <script>
        window.addEventListener('close-modal', event =>{
            $('#deleteDemandaModal').modal('hide');
            $('#agregarEventoModal').modal('hide');
            
        });
        window.addEventListener('show-delete-confirmation-modal', event =>{
            $('#deleteDemandaModal').modal('show');
        });
        window.addEventListener('show-view-demanda-modal', event =>{
            $('#viewDemandaModal').modal('show');
        });
        window.addEventListener('show-add-evento-modal', event =>{
            $('#agregarEventoModal').modal('show');
        });
        window.addEventListener('show-edit-demanda-modal', event =>{
            $('#editDemandaModal').modal('show');
        });
    </script>
@endpush