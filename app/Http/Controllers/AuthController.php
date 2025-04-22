<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     summary="Register a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                    format="email",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="login",
     *                     type="string",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'last_name' => 'required',
                'first_name' => 'required',
                'login' => 'required'
            ]);
            $user = User::create([
                'login' => $request->login,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'role_id' => 1
            ]);
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], CREATED);
        } catch (ValidationException $ex) {
            abort(INVALID_DATA, $ex->getMessage());
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/signin",
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="login",
     *                     type="string",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required',
                'password' => 'required',
            ]);
            if (!Auth::attempt($request->only('login', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $user = Auth::user();
            $token = $user->createToken($user->login);

            return ['token' => $token->plainTextToken];
        } catch (ValidationException $ex) {
            abort(INVALID_DATA, $ex->getMessage());
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/signout",
     *     summary="Logout a user",
     *  security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="login",
     *                     type="string",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged out"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    //https://stackoverflow.com/questions/64088962/darkaonline-l5-swagger-with-laravel-sanctum-swagger-ui pour faire marcher sanctum avec swagger, change dans les config aussi
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            $user->tokens()->delete();
            return response()->noContent();
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }

}
