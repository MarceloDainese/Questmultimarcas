<?php

use App\Http\Controllers\Main;
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

Route::get('/', [Main::class, 'index'])->name('login');
Route::get('/logout', [Main::class, 'logout'])->name('logout');
Route::post('/auth', [Main::class, 'auth'])->name('auth');


Route::middleware(['admin'])->group(function(){
    Route::delete('/delete{id?}', [Main::class, 'deleteCar'])->name('deleteCar');// deleta carro
    Route::get('/search', [Main::class, 'search'])->name('search');// envio do input pesquisar

    Route::get('/capturar', [Main::class, 'search'])->name('capturar');// pesquisa no site Quest e insere os dados na base de dados MySql

    Route::get('/list', [Main::class, 'listCars'])->name('list');// Lista os Carros da Base de Dados
});

