<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API de Gerenciamento de Tarefas",
 *     version="1.0.0",
 *     description="API para gerenciar tarefas, categorias e status.",
 *     @OA\Contact(
 *         email="pedro.balestra@outlook.com",
 *         name="Pedro Balestra"
 *     ),
 * @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Tarefa",
 *     type="object",
 *     required={"titulo"},
 *     @OA\Property(property="id", type="integer", description="ID único da tarefa"),
 *     @OA\Property(property="titulo", type="string", description="Título da tarefa"),
 *     @OA\Property(property="descricao", type="string", description="Descrição da tarefa"),
 *     @OA\Property(property="status", type="string", enum={"Em andamento", "Concluído", "Pendente"}, description="Status da tarefa"),
 *     @OA\Property(property="categoria", type="string", enum={"Casa", "Estudos", "Lazer", "Mercado", "Contas", "Outros"}, description="Categoria da tarefa"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data da última atualização")
 * )
 */
class TarefaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tarefas",
     *     summary="Listar todas as tarefas",
     *     tags={"Tarefas"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar tarefas pelo status.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"Em andamento", "Concluído", "Pendente"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas retornada com sucesso.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Tarefa")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Tarefa::all();
    }

    /**
     * @OA\Post(
     *     path="/api/tarefas",
     *     summary="Criar uma nova tarefa",
     *     tags={"Tarefas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo"},
     *             @OA\Property(property="titulo", type="string", example="Minha nova tarefa"),
     *             @OA\Property(property="descricao", type="string", example="Detalhes da tarefa"),
     *             @OA\Property(property="status", type="string", example="Em andamento"),
     *             @OA\Property(property="categoria", type="string", example="Casa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/Tarefa")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validateInput($request);

        $titulo = $request->input('titulo');
        $descricao = $request->input('descricao', 'Nada');
        $status = $request->input('status', 'Em andamento');
        $categoria = $request->input('categoria', 'Outros');

        $tarefa = Tarefa::create([
            'titulo' => $titulo,
            'descricao' => $descricao,
            'status' => $status,
            'categoria' => $categoria
        ]);

        return response()->json($tarefa, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tarefas/{id}",
     *     summary="Obter os detalhes de uma tarefa",
     *     tags={"Tarefas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da tarefa retornados com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/Tarefa")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     )
     * )
     */
    public function show($id)
    {
        $tarefa = Tarefa::find($id);
        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada ou já deletada'], 404);
        }
        return $tarefa;
    }

    /**
     * @OA\Put(
     *     path="/api/tarefas/{id}",
     *     summary="Atualizar uma tarefa",
     *     tags={"Tarefas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Título atualizado"),
     *             @OA\Property(property="descricao", type="string", example="Descrição atualizada"),
     *             @OA\Property(property="status", type="string", example="Concluído"),
     *             @OA\Property(property="categoria", type="string", example="Casa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/Tarefa")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $tarefa = Tarefa::findOrFail($id);

        $tarefa->titulo = $request->input('titulo', $tarefa->titulo);
        $tarefa->descricao = $request->input('descricao', $tarefa->descricao);
        $tarefa->status = $request->input('status', $tarefa->status);
        $tarefa->categoria = $request->input('categoria', $tarefa->categoria);

        if ($tarefa->status === 'Concluído' && !$tarefa->completed_at) {
            $tarefa->completed_at = now();
        } elseif ($tarefa->status !== 'Concluído') {
            $tarefa->completed_at = null;
        }

        $tarefa->save();

        return response()->json($tarefa, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tarefas/{id}",
     *     summary="Excluir uma tarefa",
     *     tags={"Tarefas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tarefa excluída com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada."
     *     )
     * )
     */
    public function delete($id)
    {
        $tarefa = Tarefa::find($id);
        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada ou já deletada'], 404);
        }
        Tarefa::destroy($id);

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

        if ($descricao && !preg_match('/^[a-zA-Z0-9\s\-]+$/', $descricao)) {
            return response()->json(['message' => 'A descrição deve conter apenas letras, números e espaços.'], 400)->throwResponse();
        }
    }
}
