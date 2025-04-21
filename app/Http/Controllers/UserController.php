<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\LanguageResource;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;



class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     tags={"Users"},
     *     summary="Gets one user",
     *     @OA\Response(
     *         response=200,
     *         description="shows the user"
     *     ),
     *     @OA\Parameter(
     *         description="user ID",
     *         required=true,
     *         type="integer"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $user = $this->userRepository->getById($id);
        } catch (Exception $e) {
            return response()->json(['An error occured: ' => $e->getMessage()], 404);
        }
    }
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string',
            ]);

            $userId = auth()->id();

            $updatedUser = $this->userRepository->update($userId, [
                'password' => bcrypt($validated['password']),
            ]);

            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
    }

}
