<?php

use App\Http\Controllers\TarefaController;

Route::get('/tarefas', [TarefaController::class, 'index']);
Route::post('/tarefas', [TarefaController::class, 'store']);
Route::get('/tarefas/{id}', [TarefaController::class, 'show']);
Route::put('/tarefas/{id}', [TarefaController::class, 'update']);
Route::delete('/tarefas/{id}', [TarefaController::class, 'delete']);
