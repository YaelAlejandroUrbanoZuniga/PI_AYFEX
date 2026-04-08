<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::post('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/register', function () {
    
    return view('auth.register'); 
})->name('register');


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



Route::get('/pedidos', function () {
    return view('pedidos'); 
})->name('pedidos');

Route::get('/crear', function () {
    return view('crear'); 
})->name('crear');

Route::get('/perfil', function () {
    return view('perfil'); 
})->name('perfil');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/envios', function () {
    return view('envios'); 
})->name('envios');

Route::get('/clientes', function () {
    return view('clientes'); 
})->name('clientes');

Route::get('/operadores', function () {
    return view('operadores'); 
})->name('operadores');
Route::get('/rutas', function () { 
    return view('rutas');
    })->name('rutas');
Route::get('/reportes', function () {
     return view('reportes');
      })->name('reportes');
Route::get('/incidencias', function () {
     return view('incidencias');
      })->name('incidencias');
Route::get('/perfil', function () {
     return view('perfil'); 
     })->name('perfil');
Route::get('/registro', function () {
    // Como tu archivo está suelto en la raíz de "views", solo ponemos su nombre
    return view('registro'); 
})->name('registro');