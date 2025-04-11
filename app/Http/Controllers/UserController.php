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
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = auth()->user();
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the password'], 500);
        }
    }
}
