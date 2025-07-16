<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/games",
     *     tags={"Games"},
     *     summary="Lista todos os jogos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de jogos retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Jogos não encontrados"
     *     )
     * )
     */
    public function index()
    {
        $games = Game::all();

        if ($games->isEmpty()) {
            return response()->json(["message" => "Nenhum jogo encontrado"], 404);
        }
        return response()->json($games);
    }

    /**
     * @OA\Post(
     *     path="/api/games",
     *     tags={"Games"},
     *     summary="Criar um novo jogo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "publisher", "platforms"},
     *                 @OA\Property(property="title", type="string", example="Hollow Knight"),
     *                 @OA\Property(property="publisher", type="string", example="Team Cherry"),
     *                 @OA\Property(property="release_date", type="string", example="2021-02-20"),
     *                 @OA\Property(property="platforms", type="string", example="Nintendo Switch, PS4, PS5, Xbox, PC")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jogo criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar jogo"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            "title" => ["required"],
            "publisher" => ["required"],
            "release_date" => ["nullable", "date"],
            "platforms" => ["required"]
        ];

        $messages = [
            "title.required" => "O título do jogo é obrigatório.",
            "publisher.required" => "O publisher do jogo é obrigatório.",
            "release_date.date" => "A data de lançamento deve ser uma data válida.",
            "platforms.required" => "As plataformas do jogo são obrigatórias."
        ];

        $request->validate($rules, $messages);
        try {
            $games = Game::create($request->all());
            return response()->json($games, 201);
        } catch (\Throwable $e) {
            return response()->json(["message" => "Erro ao cadastrar jogo: " . $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Listar jogo pelo Id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jogo encontrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jogo não encontrados"
     *     )
     * )
     */
    public function show($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(["message" => "Jogo não encontrado"], 404);
        }
        return response()->json($game);
    }


    /**
     * @OA\Put(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Atualizar o jogo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "publisher", "platforms", "_method"},
     *                 @OA\Property(property="title", type="string", example="Hollow Knight"),
     *                 @OA\Property(property="publisher", type="string", example="Team Cherry"),
     *                 @OA\Property(property="release_date", type="string", example="2021-02-20"),
     *                 @OA\Property(property="platforms", type="string", example="Nintendo Switch, PS4, PS5, Xbox, PC"),
     *                 @OA\Property(property="_method", type="string", example="put")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jogo atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jogo não encontrados"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar jogo"
     *     )
     * )
     */
    public function update(Request $request, Game $game)
    {
        $rules = [
            "title" => ["required"],
            "publisher" => ["required"],
            "release_date" => ["nullable", "date"],
            "platforms" => ["required"]
        ];

        $messages = [
            "title.required" => "O título do jogo é obrigatório.",
            "publisher.required" => "O publisher do jogo é obrigatório.",
            "release_date.date" => "A data de lançamento deve ser uma data válida.",
            "platforms.required" => "As plataformas do jogo são obrigatórias."
        ];

        $request->validate($rules, $messages);

        try {
            $game->update($request->all());
            return response()->json($game);
        } catch (\Throwable $e) {
            return response()->json(["message" => "Erro ao atualizar o jogo: ".$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Deletar jogo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method"},
     *                 @OA\Property(property="_method", type="string", example="delete")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jogo deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jogo não encontrados"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar jogo"
     *     )
     * )
     */
    public function destroy(Game $game)
    {
        try{
            $game->delete();
            return response()->json(["message" => "Jogo deletado com sucesso"], 200);
        }catch(\Throwable $e)
        {
            return response()->json(["message" => "Erro ao deletar o jogo: ".$e->getMessage()], 500);
        }
    }
}
