<div>
@include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>Gestion de Facturas</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Añadir Facturas de las Demandas</h4>
        </div>
        <div class="card-body">

            <x-auth-validation-errors class="mb-4" :errors="$errors" context="add_demand" userName="{{auth()->user()->name}}" 
                x-data="{ showError: true }"
                x-show="showError"
                x-init="setTimeout(() => { showError = false; }, 10000)"/>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <x-label for="NR_DEMANDA" :value="__('Nro Demanda')" />
                    <x-input id="NR_DEMANDA" type="text" name="NR_DEMANDA" :value="old('NR_DEMANDA')" wire:model='NR_DEMANDA' maxlength="15"/>
                </div>
                <div class="form-group col-md-3">
                    <x-label for="FE_EMISION" :value="__('Fecha Emision')" />
                    <x-input id="FE_EMISION" type="date" name="FE_EMISION" :value="old('FE_EMISION')" wire:model='FE_EMISION' />
                </div>
                <div class="form-group col-md-3">
                    <x-label for="FECHA_PRESENTACION" :value="__('Fecha Presentacion')" />
                    <x-input id="FECHA_PRESENTACION" type="date" name="FECHA_PRESENTACION" :value="old('FECHA_PRESENTACION')" wire:model='FECHA_PRESENTACION' />
                </div>
                <div class="form-group col-md-3">
                    <x-label for="COD_ESTUDIO" :value="__('Codigo Estudio')" />
                    <select id="COD_ESTUDIO" name="COD_ESTUDIO" class="form-control" wire:model='COD_ESTUDIO'>
                        <option value="">Selecciona un código de estudio</option>
                        @foreach ($estudios as $estudio)
                            <option value="{{ $estudio->COD_ESTUDIO }}">{{ $estudio->COD_ESTUDIO }} - {{ $estudio->NOMBRE_EST }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <fieldset class="bg-light" style="padding: 10px;">
                <legend>Empresa</legend>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <x-label for="RUC_EMPLEADOR" :value="__('RUC')" />
                        <x-input id="RUC_EMPLEADOR" type="text" name="RUC_EMPLEADOR" :value="old('RUC_EMPLEADOR')" wire:model.lazy='RUC_EMPLEADOR' wire:change="buscarEmpresa" maxlength="11" />
                    </div>
                    <div class="form-group col-md-6">
                        <x-label for="RAZON_SOCIAL" :value="__('Razon Social')" />
                        <x-input id="RAZON_SOCIAL" type="text" name="RAZON_SOCIAL" :value="old('RAZON_SOCIAL')" wire:model='RAZON_SOCIAL' />
                    </div>
                    <div class="form-group col-md-3">
                        <x-label for="TIPO_EMPRESA" :value="__('Tipo Empresa')" />
                        <select id="TIPO_EMPRESA" name="TIPO_EMPRESA" class="form-control" wire:model='TIPO_EMPRESA'>
                            <option value="">Selecciona el tipo</option>
                            <option value="1">PUBLICA</option>
                            <option value="2">PRIVADA</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <x-label for="DIRECC" :value="__('Direccion')" />
                        <x-input id="DIRECC" type="text" name="DIRECC" :value="old('DIRECC')" wire:model='DIRECC' />
                    </div>
                    <div class="form-group col-md-4">
                        <x-label for="LOCALI" :value="__('Localizacion')" />
                        <x-input id="LOCALI" type="text" name="LOCALI" :value="old('LOCALI')" wire:model='LOCALI' />
                    </div>
                    <div class="form-group col-md-4">
                        <x-label for="REFERENCIA" :value="__('Referencia')" />
                        <x-input id="REFERENCIA" type="text" name="REFERENCIA" :value="old('REFERENCIA')" wire:model='REFERENCIA' />
                    </div>
                </div>
                <div class="form-row">
                <div class="form-group col-md-3">
                    <x-label for="DEPARTAMENTO" :value="__('Departamento')" />
                    <select id="DEPARTAMENTO" name="DEPARTAMENTO" wire:model="DEPARTAMENTO" class="form-control">
                        <option value="">Seleccione un departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->ID_D }}">{{ $departamento->DEPARTAMENTO }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <x-label for="PROVINCIA" :value="__('Provincia')" />
                    <select id="PROVINCIA" name="PROVINCIA" wire:model="PROVINCIA" class="form-control">
                        <option value="">Seleccione una provincia</option>
                        @foreach ($provincias as $provincia)
                            <option value="{{ $provincia->ID_P }}">{{ $provincia->PROVINCIA }}</option>
                        @endforeach
                    </select>
                </div>
                

                <div class="form-group col-md-3">
                    <x-label for="DISTRITO" :value="__('Distrito')" />
                    <select id="DISTRITO" name="DISTRITO" wire:model="DISTRITO" class="form-control">
                        <option value="">Seleccione un distrito</option>
                        @foreach ($distritos as $dist)
                            <option value="{{ $dist->ID_DIST }}">{{ $dist->DISTRITO }}</option>
                        @endforeach
                    </select>
                </div>

                    <div class="form-group col-md-3">
                        <x-label for="TELEFONO" :value="__('Telefono')" />
                        <x-input id="TELEFONO" type="text" name="TELEFONO" :value="old('TELEFONO')" wire:model='TELEFONO' maxlength="9" pattern="\d*"/>
                    </div>
                </div>
            </fieldset >
            <br>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <x-label for="MTO_TOTAL_DEMANDA" :value="__('Monto Total Demanda')" />
                    <x-input id="MTO_TOTAL_DEMANDA" type="number" name="MTO_TOTAL_DEMANDA" :value="old('MTO_TOTAL_DEMANDA')" wire:model='MTO_TOTAL_DEMANDA' pattern="\d+(\.\d+)?" />
                </div>
                <div class="form-group col-md-3">
                    <x-label for="TIP_DEUDA" :value="__('Tipo de Deuda')" />
                    <select id="TIP_DEUDA" name="TIP_DEUDA" class="form-control" wire:model='TIP_DEUDA'>
                        <option value="">Selecciona el tipo de deuda</option>
                        @foreach ($deudas as $deuda)
                            <option value="{{ $deuda->TIP_DEUDA }}">{{ $deuda->TIP_DEUDA }} - {{ $deuda->DESCRIPCION_DEUDA }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <x-label for="CODIGO_UNICO_EXPEDIENTE" :value="__('Codigo Unico de Expediente')" />
                    <x-input id="CODIGO_UNICO_EXPEDIENTE" type="text" name="CODIGO_UNICO_EXPEDIENTE" :value="old('CODIGO_UNICO_EXPEDIENTE')" wire:model='CODIGO_UNICO_EXPEDIENTE' maxlength="26"/>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <x-label for="EXPEDIENTE" :value="__('Expediente')" />
                    <x-input id="EXPEDIENTE" type="text" name="EXPEDIENTE" :value="old('EXPEDIENTE')" wire:model='EXPEDIENTE' />
                </div>
                <div class="form-group col-md-2">
                    <x-label for="AÑO" :value="__('Año')" />
                    <x-input id="AÑO" type="number" name="AÑO" :value="old('AÑO')" wire:model='AÑO' />
                </div>
                <div class="form-group col-md-6">
                    <x-label for="SECRETARIO_JUZGADO" :value="__('Secretario del Juzgado')" />
                    <x-input id="SECRETARIO_JUZGADO" type="text" name="SECRETARIO_JUZGADO" :value="old('SECRETARIO_JUZGADO')" wire:model='SECRETARIO_JUZGADO' />
                </div>
                <div class="form-group col-md-2">
                    <x-label for="CODIGO_JUZGADO" :value="__('Codigo Juzgado')" />
                    <x-input id="CODIGO_JUZGADO" type="number" name="CODIGO_JUZGADO" :value="old('CODIGO_JUZGADO')" wire:model='CODIGO_JUZGADO' />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <x-label for="DESCRIPCION_JUZGADO" :value="__('Descripcion Juzgado')" />
                    <x-input id="DESCRIPCION_JUZGADO" type="text" name="DESCRIPCION_JUZGADO" :value="old('DESCRIPCION_JUZGADO')" wire:model='DESCRIPCION_JUZGADO' />
                </div>
            </div>
            <fieldset class="bg-light p-3">
                <legend>Evento</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="CODIGO_EVENTO" class="form-label">{{ __('Codigo Evento') }}</label>
                            <select id="CODIGO_EVENTO" name="CODIGO_EVENTO" class="form-control" wire:model='CODIGO_EVENTO'>
                                <option value="">Seleccione un Evento</option>
                                @foreach ($eventos as $evento)
                                    @if ($evento->CODIGO_EVENTO == '100')
                                        <option value="{{ $evento->CODIGO_EVENTO }}">{{ $evento->CODIGO_EVENTO }} - {{ $evento->DESCRIPCION_EVENTO }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FECHA_EVENTO" class="form-label">{{ __('Fecha Evento') }}</label>
                            <input id="FECHA_EVENTO" type="date" name="FECHA_EVENTO" class="form-control" wire:model='FECHA_EVENTO' />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <x-label for="ID_REGISTRO" :value="__('Tipo de Registro')" />
                        <select id="ID_REGISTRO" name="ID_REGISTRO" class="form-control" wire:model='ID_REGISTRO'>
                            <option value="">Selecciona el Registro</option>
                            @foreach ($registros as $registro)
                                <option value="{{ $registro->ID_REGISTRO }}">{{ $registro->MODO_REGISTRO }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($ID_REGISTRO == 2)
                    <div class="form-group col-md-3">
                        <x-label for="ID_UBICACION" :value="__('Ubicacion')" />
                        <select id="ID_UBICACION" name="ID_UBICACION" class="form-control" wire:model='ID_UBICACION'>
                            <option value="">Selecciona la Ubicacion</option>
                            @foreach ($ubicaciones as $ubi)
                                <option value="{{ $ubi->ID_UBICACION }}">{{ $ubi->NOMBRE_UBICACION }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </fieldset>

            <br>

            <x-button type='submit' wire:click='addDemandaPrima'>
                {{ __('Registrar') }}
            </x-button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('fechaPresentacionChanged', fechaPresentacion => {
                document.getElementById('FECHA_EVENTO').value = fechaPresentacion;
                Livewire.emit('fechaEventoChanged', fechaPresentacion);
            });

            Livewire.on('fechaEventoChanged', fechaEvento => {
                document.getElementById('FECHA_PRESENTACION').value = fechaEvento;
            });
        });
    </script>
@endpush