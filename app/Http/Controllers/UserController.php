<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\LanguageResource;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     tags={"Albums"},
     *     summary="Gets one album",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Parameter(
     *         description="Album ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $user = UserRepository::find($id);
        } catch (Exception $e) {
            return response()->json(['An error occured: ' => $e->getMessage()], 404);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['An error occured: ' => $e->getMessage()], 500);
        }
    }

}
