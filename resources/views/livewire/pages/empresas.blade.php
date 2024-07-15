<div>
@include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>Empresas</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Actividad de Empresas</h4>
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
                            <label>Importar / Actualizar Data   </label>
                            <br>
                            <div class="input-group">
                              <input type="file" class="form-control" wire:model="excel" id="excel" class="form-control-file" wire:loading.attr="disabled" wire:target="excel" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                              @if ($excel != null)
                                  <button class="btn btn-outline-primary" type="button" id="excel" wire:click="importar" wire:loading.attr="disabled" wire:target="excel">Importar</button>
                              @endif
                            </div>
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
                        <label for="filtroDepartamento">Filtrar por Departamento:</label>
                        <select id="filtroDepartamento" wire:model="filtroDepartamento" class="form-control">
                          <option value="">Todos</option>
                          @foreach ($departamentos as $departamento)
                              <option value="{{ $departamento }}">{{ $departamento}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="filtroTipoEmpresa">Filtrar por Tipo:</label>
                        <select id="filtroTipoEmpresa" wire:model="filtroTipoEmpresa" class="form-control">
                          <option value="">Todos</option>
                          @foreach ($tipos as $tipo)
                            @if ($tipo == 1){
                              <option value="1">PRIVADA</option>
                            }@else{
                              <option value="2">PUBLICA</option>
                            }@endif
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
                          <th scope="col" width="9%">
                              <div class="d-flex">
                                  <input type="checkbox" id="checkAll">
                              </div>
                          </th>
                          <th scope="col" wire:click="sortBy('RUC_EMPLEADOR')" style="cursor: pointer">RUC</th>
                          <th scope="col" wire:click="sortBy('RAZON_SOCIAL')" style="cursor: pointer">Razon Social</th>
                          <th scope="col" wire:click="sortBy('CORREO')" style="cursor: pointer">Correo Representante</th>
                          <th scope="col" width="5%" wire:click="sortBy('TELEFONO')" style="cursor: pointer">Telefono Representante</th>
                          <th scope="col" width="10%">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($empresas->isEmpty())
                            <tr>
                                <td colspan="5">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($empresas as $empresa)
                            <tr wire:key="{{ $empresa->id }}">
                                <td>
                                <div class="d-flex">
                                    
                                        <input type="checkbox" wire:click="toggleRucSeleccionado('{{ $empresa->RUC_EMPLEADOR }}')" 
                                            {{ in_array($empresa->RUC_EMPLEADOR, $rucSeleccionados) ? 'checked' : '' }}>
                                        
                                            <span>-{{ ($empresas->currentpage() - 1) * $empresas->perpage() + $loop->index + 1 }}</span>

                                    
                                </div>
                                    
                                </td>
                                <td>{{ $empresa->RUC_EMPLEADOR }}</td>
                                <td>{{ $empresa->RAZON_SOCIAL }}</td>
                                <td>{{ $empresa->representante->RL_CORREO ?? 'No disponible' }}</td>
                                <td>{{ $empresa->representante->RL_TELEFONO ?? 'No disponible' }}</td>
                                <td>
                                  <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                    data-placement="top" title="Ver Envios" wire:click="viewEnvios({{ $empresa->RUC_EMPLEADOR }})">
                                    <i class="fas fa-envelope text-danger"></i>
                                  </button>
                                  <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                    data-placement="top" title="Detalle" wire:click="viewDetalle({{ $empresa->RUC_EMPLEADOR }})">
                                    <i class="fas fa-eye text-primary"></i>
                                  </button>
                                  
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $empresas->links() }}
            </div>    
        </div>
        <div class="card-footer">
            <p>
            @if (count($rucSeleccionados) > 0)
                <button class="btn btn-primary" data-toggle="modal" data-target="#PanelCorreo">Enviar Correo</button>
            @endif
            </p>
        </div>
    </div>

    <!-- Modal -->

    <!-- Modal Detalles-->

    <div wire:ignore.self class="modal fade" id="PanelVerDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="details-section">
                                <p><strong>RUC:</strong> {{$verRuc}}</p>
                                <p><strong>Razon Social:</strong> {{$verRazonSocial}}</p>
                                <p><strong>Tipo Empresa:</strong> {{$verTipoEmpresa}}</p>
                                <p><strong>Direccion:</strong> {{$verDireccion}}</p>
                                <p><strong>Referencia:</strong> {{$verReferencia}}</p>
                                <p><strong>UBIGEO:</strong> {{$verDistrito}} - {{$verProvincia}} - {{$verDepartamento}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="details-section">
                                <p><strong>Nombre Representante:</strong> {{$verRpL}}</p>
                                <p><strong>Telefono Representante:</strong> {{$verTelefono}}</p>
                                <p><strong>Correo Representante:</strong> {{$verCorreo}}</p>
                                <p><strong>Contactos adicionales:</strong>
                                  <ul>
                                      @forelse($datosEmpresa as $dato)
                                          @if($dato['TELEFONO'] != null)
                                              <li>{{$dato['TELEFONO']}}</li>
                                          @endif
                                      @empty
                                          <li>No hay datos adicionales.</li>
                                      @endforelse
                                      @forelse($datosEmpresa as $dato)
                                          @if($dato['CORREO'] != null)
                                              <li>{{$dato['CORREO']}}</li>
                                          @endif
                                      @empty
                                          <li>No hay datos adicionales.</li>
                                      @endforelse
                                  </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Enviar Correo-->
    <div wire:ignore.self class="modal fade" id="PanelCorreo" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Enviar Correos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                      <div class="form-group">
                          <label for="tipoAFP">Tipo de AFP:</label>
                          <select class="form-control" wire:model="tipoAFP" id="tipoAFP">
                              <option value="PRIMA">PRIMA</option>
                              <option value="PROFUTURO">PROFUTURO</option>
                          </select>
                      </div>
                        <div class="form-group">
                            <label for="correoAsunto">Destinatario:</label>
                            <p>
                                @foreach ($rucSeleccionados as $ruc)
                                {{ $ruc }} /
                                @endforeach
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="correoAsunto">Asunto:</label>
                            <input type="text" class="form-control" wire:model="correoAsunto" id="correoAsunto" value="COBRANZA JUDICIAL PROFUTUO AFP - RUC [RUC_EMPLEADOR] - [RAZON_SOCIAL] - [DEPARTAMENTO]">
                        </div>
                        <div class="form-group">
                            <label for="correoMensaje">Mensaje:</label>
                            <textarea wire:model="correoMensaje" id="correoMensaje" style="width: 100%; height: 300px;">
                            </textarea>
                        </div>
                        <div class="form-group">
                          <label for="correoFirma">Firma:</label><br>
                          <div wire:model="correoFirma" id="correoFirma">
                            {{ auth()->user()->name }} <br>
                            {{ auth()->user()->email }} <br>
                            EJECUTIVO DE COBRANZA - AREA LEGAL <br>
                            FERNÁNDEZ NUÑEZ ASOCIADOS SRL
                          </div>
                        </div>
                        <!-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="enviarWhatsapp" id="enviarWhatsapp">
                            <label class="form-check-label" for="enviarWhatsapp">Enviar también a WhatsApp</label>
                        </div> -->
                    </form>
                </div>
                <div class="modal-footer">
                  <button wire:click="enviarCorreo" wire:loading.attr="disabled" class="contactButton">Enviar
                      <div class="iconButton">
                          <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
                          </svg>
                      </div>
                  </button>                
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <div wire:loading wire:target="enviarCorreo">
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
    </div>


    <!-- Modal Ver Correos-->
    <div wire:ignore.self class="modal fade" id="PanelVerCorreos" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document" >
            <div class="modal-content">
                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel"><strong>Correos Enviados</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    @if(count($verRegistrosAsociados) > 0)
                        <ul class="list-group">
                            @foreach($verRegistrosAsociados as $fecha)
                                <li class="list-group-item">{{ $fecha }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>No hay registros de correo enviado.</p>
                    @endif
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
        window.addEventListener('cerrarModal', event =>{
            $('#PanelCorreo').modal('hide');       
        });

        window.addEventListener('show-view-detalle-modal', event =>{
            $('#PanelVerDetalles').modal('show');       
        });

        window.addEventListener('show-view-correo-modal', event =>{
            $('#PanelVerCorreos').modal('show');
        });
    </script>
@endpush
