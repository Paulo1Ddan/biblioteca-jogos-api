<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GameLibraryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/library/{user_id}",
     *     tags={"Biblioteca"},
     *     summary="Lista todos os jogos do usuário na biblioteca",
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
    public function index(User $user)
    {
        if ($user->games->isEmpty()) {
            return response()->json(["mesage" => "Sua biblioteca está vazia"]);
        }
        return response()->json($user->games);
    }

    /**
     * @OA\Post(
     *     path="/api/library/add",
     *     tags={"Biblioteca"},
     *     summary="Adiciona o jogo a biblioteca",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"user_id", "game_id"},
     *                 @OA\Property(property="user_id", type="int", example="1"),
     *                 @OA\Property(property="game_id", type="int", example="1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jogo adicionado a biblioteca"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="O jogo já está presente na biblioteca"
     *     )
     * )
     */
    public function addGame(Request $request)
    {
        $rules = [
            "user_id" => ["required", "exists:users"],
            "game_id" => ["required", "exists:games"]
        ];

        $messages = [
            "user_id.required" => "O campo id do usuário é obrigatório.",
            "user_id.exists" => "Usuário inválido.",
            "game_id.required" => "O campo id do jogo é obrigatório.",
            "game_id.exists" => "Jogo inválido.",
        ];

        $request->validate($rules, $messages);

        $user = User::find($request->user_id);
        if ($user->games()->where('game_id', $request->game_id)->exists()) {
            return response()->json(["message" => "O jogo já está presente na biblioteca"]);
        }
        $user->games()->attach($request->game_id);
        return response()->json(["message" => "O jogo foi adicionado a sua biblioteca"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/library/remove",
     *     tags={"Biblioteca"},
     *     summary="Remove o jogo a biblioteca",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"user_id", "game_id", "_method"},
     *                 @OA\Property(property="user_id", type="int", example="1"),
     *                 @OA\Property(property="game_id", type="int", example="1"),
     *                 @OA\Property(property="_method", type="string", example="delete")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jogo removido a biblioteca"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Esse jogo não está na sua lista"
     *     )
     * )
     */
    public function removeGame(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user->games()->where('game_id', $request->game_id)->exists()) {
            return response()->json(["message" => "Esse jogo não está na sua lista"]);
        }
        $user->games()->detach($request->game_id);
        return response()->json(["message" => "O jogo foi removido da sua biblioteca"]);
    }
}
