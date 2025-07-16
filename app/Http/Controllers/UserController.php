<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

/**
 * @OA\Info(
 *     title="Documentação do gerenciamento de biblioteca de jogos 🚀",
 *     version="1.0.0",
 *     description="Esta é a documentação gerada pelo Swagger para a minha API de gerenciamento de biblioteca de jogos."
 * )
 */

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Listar todos os usuários",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Usuários não encontrados"
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json(["message" => "Usuários não encontrados"], 404);
        }
        return response()->json($users);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Criar um novo usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "email", "password"},
     *                 @OA\Property(property="name", type="string", example="João da Silva"),
     *                 @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="senha123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar usuário"
     *     )
     * )
     */
    public function store(Request $request)
    {

        $rules = [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users,email"],
            "password" => ["required", Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised()],
        ];
        $messages = [
            "name.required" => "O campo nome é obrigatório.",
            "email.required" => "O campo email é obrigatório.",
            "email.email" => "O campo email deve ser um endereço de email válido.",
            "email.unique" => "O email informado já está em uso.",
            "password.required" => "O campo senha é obrigatório.",
            "password.min" => "A senha deve ter pelo menos 12 caracteres.",
            "password.mixedCase" => "A senha deve conter letras maiúsculas e minúsculas.",
            "password.numbers" => "A senha deve conter números.",
            "password.symbols" => "A senha deve conter símbolos.",
        ];

        $request->validate(
            $rules,
            $messages
        );

        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);
            return response()->json($user, 201);
        } catch (\Throwable $e) {
            return response()->json(["message" => "Erro ao criar usuário: " . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Listar usuário pelo Id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrados"
     *     )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "Usuário não encontrado"], 404);
        }
        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Atualizar o usuário",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
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
     *                 required={"name", "email", "password", "_method"},
     *                 @OA\Property(property="name", type="string", example="João da Silva"),
     *                 @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="senha123"),
     *                 @OA\Property(property="_method", type="string", example="put")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrados"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar usuário"
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],
            "password" => ["nullable", Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised()],
        ];
        $messages = [
            "name.required" => "O campo nome é obrigatório.",
            "email.required" => "O campo email é obrigatório.",
            "email.email" => "O campo email deve ser um endereço de email válido.",
            "email.unique" => "O email informado já está em uso.",
            "password.min" => "A senha deve ter pelo menos 12 caracteres.",
            "password.mixedCase" => "A senha deve conter letras maiúsculas e minúsculas.",
            "password.numbers" => "A senha deve conter números.",
            "password.symbols" => "A senha deve conter símbolos.",
        ];

        $request->validate(
            $rules,
            $messages
        );

        try {
            $user->update($request->all());
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(["message" => "Erro ao atualizar usuário: " . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Deletar usuário",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
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
     *         description="Usuário deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrados"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar usuário"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(["message" => "Usuário deletado com sucesso"], 200);
        } catch (\Throwable $e) {
            return response()->json(["message" => "Erro ao deletar usuário: " . $e->getMessage()], 500);
        }
    }
}
