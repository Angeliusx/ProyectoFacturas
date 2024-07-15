<div>
    <x-slot name="header">
        <div class="section-header">
            <h1>Administracion</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Actividad de los pagos</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="idUsuarioFiltro">Filtrar por Demanda:</label>
                    <input wire:model="busqueda" type="text" class="form-control" placeholder="Buscar...">
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">#</th>
                                <th>Fecha</th>
                                <th>Recibo</th>
                                <th>Total Recibido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($administracion->isEmpty())
                                <tr>
                                    <td colspan="4">No hay pagos registrados</td>
                                </tr>
                            @else
                                @foreach ($administracion as $pago)
                                    <tr>
                                        
                                    </tr>
                                @endforeach
                            @endif

                            
                        </tbody>
                    </table>
                    {{ $administracion->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
