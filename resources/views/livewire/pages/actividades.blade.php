<div>
    <x-slot name="header">
        <div class="section-header">
            <h1>Actividad</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Actividad de los usuarios</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="idUsuarioFiltro">Filtrar por Demanda:</label>
                    <input wire:model="busqueda" type="text" class="form-control" placeholder="Buscar...">
                </div>
                <div class="col-md-4">
                    <label for="idUsuarioFiltro">Filtrar por Usuario:</label>
                    
                    <select wire:model="idUsuarioFiltro" id="idUsuarioFiltro" class="form-control">
                        <option value="">Seleccionar ID_USUARIO</option>
                        @foreach ($opcionesID_USUARIO as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Actividad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actividades as $actividad)
                            <tr>
                                <td>{{ $actividad->usuario->name }}</td>
                                <td>{{ $actividad->ACTIVIDAD }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $actividades->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
