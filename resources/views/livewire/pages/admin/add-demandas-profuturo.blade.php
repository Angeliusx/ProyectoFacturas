<div>
@include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>Gestion de Demanda Profuturo</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Añadir Demanda Profuturo</h4>
        </div>
        <div class="card-body">

            <x-auth-validation-errors class="mb-4" :errors="$errors" context="add_demand" userName="{{auth()->user()->name}}" 
                x-data="{ showError: true }"
                x-show="showError"
                x-init="setTimeout(() => { showError = false; }, 10000)"/>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <x-label for="NUM_DEMANDA" :value="__('Nro Demanda')" />
                    <x-input id="NUM_DEMANDA" type="text" name="NUM_DEMANDA" :value="old('NUM_DEMANDA')" wire:model='NUM_DEMANDA' maxlength="15"/>
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
                        <option value="">Selecciona el tipo</option>
                            <option value="109">109 - FERNANDEZ NUÑEZ - TRUJILLO</option>
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
                            <option value="PUBLICA">PUBLICA</option>
                            <option value="PRIVADA">PRIVADA</option>
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
                    <x-label for="TOTAL_DEMANDADO" :value="__('Total Demandado')" />
                    <x-input id="TOTAL_DEMANDADO" type="number" name="TOTAL_DEMANDADO" :value="old('TOTAL_DEMANDADO')" wire:model='TOTAL_DEMANDADO' pattern="\d+(\.\d+)?" />
                </div>
                <div class="form-group col-md-3">
                    <x-label for="TIPO_DEUDA" :value="__('Tipo de Deuda')" />
                    <x-input id="TIPO_DEUDA" type="text" name="TIPO_DEUDA" :value="old('TIPO_DEUDA')" wire:model='TIPO_DEUDA'/>
                </div>
                
                <div class="form-group col-md-6">
                    <x-label for="CODIGO_UNICO_EXPEDIENTE" :value="__('Codigo Unico de Expediente')" />
                    <x-input id="CODIGO_UNICO_EXPEDIENTE" type="text" name="CODIGO_UNICO_EXPEDIENTE" :value="old('CODIGO_UNICO_EXPEDIENTE')" wire:model='CODIGO_UNICO_EXPEDIENTE' maxlength="26"/>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <x-label for="NRO_EXPEDIENTE" :value="__('Nro Expediente')" />
                    <x-input id="NRO_EXPEDIENTE" type="text" name="NRO_EXPEDIENTE" :value="old('NRO_EXPEDIENTE')" wire:model='NRO_EXPEDIENTE' />
                </div>
                <div class="form-group col-md-2">
                    <x-label for="AÑO" :value="__('Año')" />
                    <x-input id="AÑO" type="number" name="AÑO" :value="old('AÑO')" wire:model='AÑO' />
                </div>
                <div class="form-group col-md-6">
                    <x-label for="SECRETARIO" :value="__('Secretario')" />
                    <x-input id="SECRETARIO" type="text" name="SECRETARIO" :value="old('SECRETARIO')" wire:model='SECRETARIO' />
                </div>
                <div class="form-group col-md-2">
                    <x-label for="JUZGADO" :value="__('Juzgado')" />
                    <x-input id="JUZGADO" type="number" name="JUZGADO" :value="old('JUZGADO')" wire:model='JUZGADO' />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <x-label for="DESCRIPCION_JUZGADO" :value="__('Descripcion Juzgado')" />
                    <x-input id="DESCRIPCION_JUZGADO" type="text" name="DESCRIPCION_JUZGADO" :value="old('DESCRIPCION_JUZGADO')" wire:model='DESCRIPCION_JUZGADO' />
                </div>
                <div class="form-group col-md-3">
                    <x-label for="ID_SINOE" :value="__('SINOE')" />
                    <select id="ID_SINOE" name="ID_SINOE" class="form-control" wire:model='ID_SINOE'>
                        <option value="">Selecciona el SINOE</option>
                        @foreach ($sinoe as $sinoe)
                            <option value="{{ $sinoe->ID_SINOE }}">{{ $sinoe->NOMBRE_SINOE }}</option>
                        @endforeach
                    </select>
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
                            <input id="FECHA_EVENTO" type="date" name="FECHA_EVENTO" class="form-control" :value="old('FECHA_EVENTO')" wire:model='FECHA_EVENTO' />
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

            <x-button type='submit' wire:click='addDemandaProfuturo'>
                {{ __('Registrar') }}
            </x-button>
        </div>
    </div>
</div>
