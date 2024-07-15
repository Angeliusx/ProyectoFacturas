<div>
    @include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>Gestión de usuarios</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Data de Usuarios</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col">Nombre </th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col" width="5%">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th>{{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->roles->first()->display_name == 'User')
                                        <span class="badge badge-primary">User</span>
                                    @elseif ($user->roles->first()->display_name == 'Viewer')
                                        <span class="badge badge-success">Viewer</span>
                                    @else
                                        <span class="badge badge-dark">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                    @if ($user->id != auth()->user()->id)
                                        <button class="btn btn-sm btn-link" data-toggle="tooltip"
                                            data-placement="top" title="Cambiar rol"
                                            wire:click='toggleUserRole({{ $user->id }})'>
                                            <!-- Coloca el icono correspondiente al rol actual -->
                                            @if ($user->roles->first()->display_name == 'Admin')
                                                <i class="fas fa-user-shield text-info"></i>
                                            @elseif ($user->roles->first()->display_name == 'User')
                                                <i class="fas fa-user text-info"></i>
                                            @elseif ($user->roles->first()->display_name == 'Viewer')
                                                <i class="fas fa-user-ninja text-info"></i>
                                            @endif
                                        </button>

                                        <button class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top"
                                            title="Delete" wire:click='deleteConfirmation({{ $user->id }})'>
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-link" data-toggle="tooltip"data-placement="top"
                                            title="Cancel" wire:click='cancelAccion({{ $user->id }})'>
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!--Modal Detele-->

    <div wire:ignore.self class="modal fade" id="deleteUserModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmacion para eliminar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4 pb-4">
                    <h6>Estas seguro de eliminar este Usuario: {{$verName}} ?!</h6>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteUser()">Si! Eliminar</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.addEventListener('close-modal', event =>{
            $('#deleteUserModal').modal('hide');
        });
        window.addEventListener('show-delete-confirmation-modal', event =>{
            $('#deleteUserModal').modal('show');
        });
    </script>
@endpush

<script>
    $().tooltip();
</script>
