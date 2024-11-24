<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\PaymentController;


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
Route::post('/events/join/{event_id}/{ticket_id}', [EventController::class, 'joinEvent'])->middleware("auth");
Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent'])->middleware("auth");

// SUBEVENTOS
Route::get( '/events/{id}/subevents/create', [SubEventController::class, 'createSubevent'])->middleware('auth');
Route::post( '/events/{id}/subevents', [SubEventController::class, 'storeSubevent']);
Route::post('/events/{id}/subevents/join/{subevent_id}', [SubEventController::class, 'joinSubevent'])->middleware();

// TICKETS
Route::get( '/events/{id}/tickets/create', [TicketController::class, 'createTicket'])->middleware('auth');
Route::post( '/events/{id}/tickets', [TicketController::class, 'storeTicket']);

// RELATÓRIOS
Route::get('/dashboard/{event_id}', [EventController::class, 'eventReports'])->middleware();
Route::get('/events/{id}/export/{format}', [ReportExportController::class, 'export']);


//PAGAMENTO

Route::get('/checkout', [PaymentController::class, 'createPayment'])->name('checkout');
Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::get('/pending', [PaymentController::class, 'pending'])->name('payment.pending');