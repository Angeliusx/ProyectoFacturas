<?php

use App\Http\Livewire\Pages\Admin\AddUser;
use App\Http\Livewire\Pages\Admin\ViewUser;
use App\Http\Livewire\Pages\Admin\AddDemandasPrima;
use App\Http\Livewire\Pages\Admin\AddDemandasProfuturo;
use App\Http\Livewire\Pages\Administraciones;
use App\Http\Livewire\Pages\Facturas;
use App\Http\Livewire\Pages\FacturasRetrasadas;
use App\Http\Livewire\Pages\AddFacturas;
use App\Http\Livewire\Pages\Actividades;
use App\Http\Livewire\Pages\Empresas;
use App\Http\Livewire\Pages\DemandasPrima;
use App\Http\Livewire\Pages\DemandasProfuturo;
use App\Http\Controllers\PlantillaController; 


use App\Http\Livewire\Pages\Dashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

 Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Vista Normal
Route::get('/dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');

//Demanda
Route::get('/demandas-prima', DemandasPrima::class)->middleware(['auth'])->name('demandas-prima');
Route::get('/add-demandas-prima', AddDemandasPrima::class)->middleware(['auth'])->name('add-demandas-prima');
Route::get('/demandas-profuturo', DemandasProfuturo::class)->middleware(['auth'])->name('demandas-profuturo');
Route::get('/add-demandas-profuturo', AddDemandasProfuturo::class)->middleware(['auth'])->name('add-demandas-profuturo');

//Importar Excel Demanda
Route::post('/demandas-prima/importar', [DemandasPrima::class, 'importar'])->middleware(['auth'])->name('demandas-prima.importar');
Route::post('/demandas-profuturo/importar', [DemandasProfuturo::class, 'importar'])->middleware(['auth'])->name('demandas-profuturo.importar');
Route::post('/empresa/importar',[Empresa::class,'importar'])->middleware(['auth'])->name('empresa.importar');
//Actividad
Route::get('/actividades', Actividades::class)->middleware(['auth'])->name('actividades');

//Empresas
Route::get('/empresas', Empresas::class)->middleware(['auth'])->name('empresas');

//Facturas
Route::get('/facturas', Facturas::class)->middleware(['auth'])->name('facturas');
Route::get('/facturas-retrasadas', FacturasRetrasadas::class)->middleware(['auth'])->name('facturas-retrasadas');
Route::get('/add-facturas', AddFacturas::class)->middleware(['auth'])->name('add-facturas');


//Adminitracion
Route::get('/administraciones', Administraciones::class)->middleware(['auth'])->name('administraciones');

// Vista Admin
Route::get('/admin/view-user', ViewUser::class)->middleware(['auth'])->name('view-user');
Route::get('/admin/add-user', AddUser::class)->middleware(['auth'])->name('add-user');

//VISTA IA

require __DIR__ . '/auth.php';

//
Route::get('/plantilla-correo/{plantilla}', [PlantillaController::class, 'mostrar']);