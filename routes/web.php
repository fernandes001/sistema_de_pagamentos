<?php

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

Route::get('/', [
	'uses' => 'SistemaController@index',
	'as' => 'sistema.index'
])->middleware('CheckAcl');



Route::get('/login', 'LoginController@index');

Route::get('/logoff', 'LoginController@logoff');

Route::post('/login', [
	'uses' => 'LoginController@logar',
	'as' => 'login.logar'
]);



Route::get('/conta', [
	'uses' => 'ContaController@index',
	'as' => 'conta.index'
])->middleware('CheckAcl');

Route::put('/conta/{id}', [
	'uses' => 'ContaController@update',
	'as' => 'conta.update'
])->middleware('CheckAcl');



Route::get('/usuarios', [
	'uses' => 'UsuarioController@index',
	'as' => 'usuarios.index'
])->middleware('CheckAcl');

Route::get('/usuarios/{id}/edit', [
	'uses' => 'UsuarioController@edit',
	'as' => 'usuarios.edit'
])->middleware('CheckAcl');

Route::put('/usuarios/{id}', [
	'uses' => 'UsuarioController@update',
	'as' => 'usuarios.update'
])->middleware('CheckAcl');

Route::delete('/usuarios/{id}', [
	'uses' => 'UsuarioController@destroy',
	'as' => 'usuarios.destroy'
])->middleware('CheckAcl');

Route::get('/usuarios/create', [
	'uses' => 'UsuarioController@create',
	'as' => 'usuarios.create'
])->middleware('CheckAcl');

Route::post('/usuarios', [
	'uses' => 'UsuarioController@store',
	'as' => 'usuarios.store'
])->middleware('CheckAcl');


 
Route::get('/registros', [
	'uses' => 'RegistroController@index',
	'as' => 'registros.index'
])->middleware('CheckAcl');

Route::get('/registros/{id}/edit', [
	'uses' => 'RegistroController@edit',
	'as' => 'registros.edit'
])->middleware('CheckAcl');

Route::put('/registros/{id}', [
	'uses' => 'RegistroController@update',
	'as' => 'registros.update'
])->middleware('CheckAcl');

Route::get('/registros/{id}/confirmacao', [
	'uses' => 'RegistroController@confirmacao',
	'as' => 'registros.update'
])->middleware('CheckAcl');

Route::delete('/registros/{id}', [
	'uses' => 'RegistroController@destroy',
	'as' => 'registros.destroy'
])->middleware('CheckAcl');

Route::get('/registros/create', [
	'uses' => 'RegistroController@create',
	'as' => 'registros.create'
])->middleware('CheckAcl');

Route::post('/registros', [
	'uses' => 'RegistroController@store',
	'as' => 'registros.store'
])->middleware('CheckAcl');

Route::get('/registros/gerapdf', [
	'uses' => 'RegistroController@geraPdf',
	'as' => 'registros.index'
])->middleware('CheckAcl');

Route::get('/registros/geracsv', [
	'uses' => 'RegistroController@geraCsv',
	'as' => 'registros.index'
])->middleware('CheckAcl');

Route::get('/registros/{id}/logs', [
	'uses' => 'RegistroController@getLogs',
	'as' => 'registros.index'
])->middleware('CheckAcl');



Route::get('/estados/busca/{pais_id}', [
	'uses' => 'ClienteController@buscaEstadosPorPais',
	'as' => 'clientes.index'
])->middleware('CheckAcl');

Route::get('/cidades/busca/{estado_id}', [
	'uses' => 'ClienteController@buscaCidadesPorEstado',
	'as' => 'clientes.index'
])->middleware('CheckAcl');



Route::get('/clientes', [
	'uses' => 'ClienteController@index',
	'as' => 'clientes.index'
])->middleware('CheckAcl');

Route::get('/clientes/{id}/edit', [
	'uses' => 'ClienteController@edit',
	'as' => 'clientes.edit'
])->middleware('CheckAcl');

Route::put('/clientes/{id}', [
	'uses' => 'ClienteController@update',
	'as' => 'clientes.update'
])->middleware('CheckAcl');

Route::delete('/clientes/{id}', [
	'uses' => 'ClienteController@destroy',
	'as' => 'clientes.destroy'
])->middleware('CheckAcl');

Route::get('/clientes/create', [
	'uses' => 'ClienteController@create',
	'as' => 'clientes.create'
])->middleware('CheckAcl');

Route::post('/clientes', [
	'uses' => 'ClienteController@store',
	'as' => 'clientes.store'
])->middleware('CheckAcl');



Route::get('/bancos', [
	'uses' => 'BancoController@index',
	'as' => 'bancos.index'
])->middleware('CheckAcl');

Route::get('/bancos/{id}/edit', [
	'uses' => 'BancoController@edit',
	'as' => 'bancos.edit'
])->middleware('CheckAcl');

Route::put('/bancos/{id}', [
	'uses' => 'BancoController@update',
	'as' => 'bancos.update'
])->middleware('CheckAcl');

Route::delete('/bancos/{id}', [
	'uses' => 'BancoController@destroy',
	'as' => 'bancos.destroy'
])->middleware('CheckAcl');

Route::get('/bancos/create', [
	'uses' => 'BancoController@create',
	'as' => 'bancos.create'
])->middleware('CheckAcl');

Route::post('/bancos', [
	'uses' => 'BancoController@store',
	'as' => 'bancos.store'
])->middleware('CheckAcl');



Route::get('/citardys', [
	'uses' => 'CitarDysController@index',
	'as' => 'citardys.index'
])->middleware('CheckAcl');

Route::get('/citardys/{id}/edit', [
	'uses' => 'CitarDysController@edit',
	'as' => 'citardys.edit'
])->middleware('CheckAcl');

Route::get('/citardys/gerapdf', [
	'uses' => 'CitarDysController@geraPdf',
	'as' => 'citardys.index'
])->middleware('CheckAcl');

Route::put('/citardys/{id}', [
	'uses' => 'CitarDysController@update',
	'as' => 'citardys.update'
])->middleware('CheckAcl');

Route::get('/citardys/{id}/logs', [
	'uses' => 'CitarDysController@logs',
	'as' => 'citardys.index'
])->middleware('CheckAcl');

Route::get('/citardys/{id}/confirmacao', [
	'uses' => 'CitarDysController@confirmacao',
	'as' => 'citardys.update'
])->middleware('CheckAcl');

Route::delete('/citardys/{id}', [
	'uses' => 'CitarDysController@destroy',
	'as' => 'citardys.destroy'
])->middleware('CheckAcl');

Route::get('/citardys/geracsv', [
	'uses' => 'CitarDysController@geraCsv',
	'as' => 'citardys.index'
])->middleware('CheckAcl');

Route::get('/citardys/create', [
	'uses' => 'CitarDysController@create',
	'as' => 'citardys.create'
])->middleware('CheckAcl');

Route::post('/citardys', [
	'uses' => 'CitarDysController@store',
	'as' => 'citardys.store'
])->middleware('CheckAcl');



Route::get('/boletos', [
	'uses' => 'BoletoController@index',
	'as' => 'boletos.index'
])->middleware('CheckAcl');

Route::get('/boletos/{id}/edit', [
	'uses' => 'BoletoController@edit',
	'as' => 'boletos.edit'
])->middleware('CheckAcl');

Route::put('/boletos/{id}', [
	'uses' => 'BoletoController@update',
	'as' => 'boletos.update'
])->middleware('CheckAcl');

Route::get('/boletos/{id}/confirmacao', [
	'uses' => 'BoletoController@confirmacao',
	'as' => 'boletos.update'
])->middleware('CheckAcl');

Route::get('/boletos/{id}/logs', [
	'uses' => 'BoletoController@logs',
	'as' => 'boletos.index'
])->middleware('CheckAcl');

Route::delete('/boletos/{id}', [
	'uses' => 'BoletoController@destroy',
	'as' => 'boletos.destroy'
])->middleware('CheckAcl');

Route::get('/boletos/gerapdf', [
	'uses' => 'BoletoController@geraPdf',
	'as' => 'boletos.index'
])->middleware('CheckAcl');

Route::get('/boletos/geracsv', [
	'uses' => 'BoletoController@geraCsv',
	'as' => 'boletos.index'
])->middleware('CheckAcl');

Route::get('/boletos/create', [
	'uses' => 'BoletoController@create',
	'as' => 'boletos.create'
])->middleware('CheckAcl');

Route::post('/boletos', [
	'uses' => 'BoletoController@store',
	'as' => 'boletos.store'
])->middleware('CheckAcl');



Route::get('/logs', [
	'uses' => 'LogController@index',
	'as' => 'logs.index'
])->middleware('CheckAcl');

Route::get('/logs/{id}', [
	'uses' => 'LogController@show',
	'as' => 'logs.show'
])->middleware('CheckAcl');

Route::get('/logs/{id}/history', [
	'uses' => 'LogController@getRealPreviousDataDetails',
	'as' => 'logs.show'
])->middleware('CheckAcl');



Route::get('/auditoria', [
	'uses' => 'AuditoriaController@index',
	'as' => 'auditoria.index'
])->middleware('CheckAcl');

Route::get('/auditoria/{id}/edit', [
	'uses' => 'AuditoriaController@edit',
	'as' => 'auditoria.edit'
])->middleware('CheckAcl');

Route::put('/auditoria/{id}', [
	'uses' => 'AuditoriaController@update',
	'as' => 'auditoria.update'
])->middleware('CheckAcl');

Route::get('/auditoria/{id}/confirmacao', [
	'uses' => 'AuditoriaController@confirmacao',
	'as' => 'auditoria.update'
])->middleware('CheckAcl');

Route::delete('/auditoria/{id}', [
	'uses' => 'AuditoriaController@destroy',
	'as' => 'auditoria.destroy'
])->middleware('CheckAcl');

Route::get('/auditoria/create', [
	'uses' => 'AuditoriaController@create',
	'as' => 'auditoria.create'
])->middleware('CheckAcl');

Route::post('/auditoria', [
	'uses' => 'AuditoriaController@store',
	'as' => 'auditoria.store'
])->middleware('CheckAcl');

Route::get('/auditoria/{$}', [
	'uses' => 'AuditoriaController@valorBanco',
	'as' => 'auditoria.edit'
])->middleware('CheckAcl');