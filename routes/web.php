<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use App\Models\SubEvent;

// Página Inicial
Route::get('/', [EventController::class, "index"]);
Route::get('/events/create', [EventController::class, "create"])->middleware('auth');
// Exibir Evento
Route::get('/events/{id}', [EventController::class, "show"]);
Route::post('/events', [EventController::class, 'store']);
// Dashboard
Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware('auth');
// CRUD de Eventos
Route::delete('/events/{id}', [EventController::class, 'destroy'])->middleware('auth');
Route::get('/events/edit/{id}', [EventController::class, 'edit'])->middleware('auth');
Route::put('/events/update/{id}', [EventController::class, 'update'])->middleware('auth');
// Inscrição em Evento
Route::post('/events/join/{id}', [EventController::class, 'joinEvent'])->middleware("auth");

// SUBEVENTOS
Route::get( '/events/{id}/subevents', [SubEventController::class, 'createSubevents' ])->middleware('auth');
Route::post( '/events/{id}/subevents', [SubEventController::class, 'storeSubevents' ]);
