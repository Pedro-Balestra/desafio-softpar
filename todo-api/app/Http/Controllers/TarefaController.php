<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index()
    {

        return Tarefa::all();
    }

    public function store(Request $request)
    {
         $this->validateInput($request);

         $tarefa = Tarefa::create([
            'titulo' => $titulo,
            'descricao' => $descricao,
        ]);

        return response()->json($tarefa, 201);

    }

    public function show($id)
    {
        $tarefa = Tarefa::find($id);
        if(!$tarefa)
        {
            return response()->json(['message' => 'Tarefa não encontrada ou já deletada'], 404);
        };
        return Tarefa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validateInput($request);

        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($request->all());

        return response()->json($tarefa);
    }

    public function delete($id)
    {
        $tarefa = Tarefa::find($id);
        if(!$tarefa)
        {
            return response()->json(['message' => 'Tarefa não encontrada ou já deletada'], 404);
        };
        Tarefa::delete($id);

        return response()->json(null, 204);
    }

    private function validateInput(Request $request)
    {
        $titulo = $request->input('titulo');
        $descricao = $request->input('descricao');

        if (empty($titulo)) {
            return response()->json(['message' => 'O título da tarefa é obrigatório e não pode ser vazio.'], 400)->throwResponse();
        }

        if (strlen($titulo) < 3 || strlen($titulo) > 255) {
            return response()->json(['message' => 'O título deve ter pelo menos 3 e no máximo 255 caracteres.'], 400)->throwResponse();
        }

        if (!preg_match('/^[a-zA-Z0-9\s\-]+$/', $titulo)) {
            return response()->json(['message' => 'O título deve conter apenas letras, números e espaços.'], 400)->throwResponse();
        }

        if ( !preg_match('/^[a-zA-Z0-9\s\-]+$/', $descricao)) {
            return response()->json(['message' => 'A descrição deve conter apenas letras, números e espaços.'], 400)->throwResponse();
        }
    }
}

