<div>
    <x-slot name="header border-gray-700">
        <div class="section-header dark:text-gray-100">
            <h1>Dashboard</h1>
        </div>
    </x-slot>

    <div class="card dark:bg-gray-800 dark:border-gray-700">
        <div class="card-header dark:border-gray-700">
            <h4>Panel de Actualizacion del Programa</h4>
        </div>
        <div class="card-body dark:border-gray-700">
            <p>Hola soy Angello, aqui se mostrala las actualizaciones del programa como por ejemplo: </p>
            <p>Se agrego el Importar y Exportar (Falta filtros de descarga)</p>
            <p>Se agrego el Añadir Codigo de evento, para que puedan poner los eventos de las demandas</p>
            <p>Se agrego un Ver demanda, para poder ver con mas detalle la demanda</p>
            <p>cualquier cosa al wsp. Ademas de indicarte lo siguiente: </p>

            Tu tienes el rol de : 
            {{ auth()->user()->hasRole('admin') == true
                ? 'Admin , bienvenido Jefa/e'
                : 'Usuario, hola simple usuario' }}
        </div>
        <div class="card-footer">
            §§ Angello §§
        </div>
    </div>
</div>
