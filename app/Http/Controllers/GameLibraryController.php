<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GameLibraryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/library/{$user_id}",
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

        $user = User::find($request->user_id);
        if ($user->games()->where('game_id', $request->game_id)->exists()) {
            return response()->json(["message" => "O jogo já está presente na biblioteca"]);
        }
        $user->games()->attach($request->game_id);
        return response()->json(["message" => "O jogo foi adicionado a sua biblioteca"]);
    }
}
