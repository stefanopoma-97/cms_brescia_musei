<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

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


//HOME


Route::get('/', ['as' => 'home', 'uses' => 'FrontController@getHome']); //controller che manda sulla home page


Route::post('/', ['as' => 'home', 'uses' => 'FrontController@getHomeFilter']); //controller che manda sulla home page dopo filtro


//PERCORSI

Route::get('filtro_percorsi', ['as' => 'filtro_percorsi', 'uses' => 'FrontController@percorsi']); //controller che manda sulla home page


Route::post('filtro_percorsi', ['as' => 'filtro_percorsi', 'uses' => 'FrontController@percorsiFiltro']);

Route::get('conferma_percorso', ['as' => 'conferma_percorso', 'uses' => 'FrontController@confermaPercorso']);

//OPERA

Route::resource('opera', 'OperaController', ['only' => ['show','index', 'edit', 'store', 'create', 'update']]);

Route::post('/ajaxParametri', 'FrontController@ajaxDatiFiltro'); 
