<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\UpdateGameRequest;
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

        if($games->isEmpty())
        {
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
        try{
            $games = Game::create($request->all());
            return response()->json($games, 201);
        }catch(\Throwable $e){
            return response()->json(["message" => "Erro ao cadastrar jogo: ".$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
