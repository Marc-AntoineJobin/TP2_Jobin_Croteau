<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;

class CriticController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/critic",
     * tags={"critic"},
     * summary="Creates a critic",
     * @OA\Response(
     *     response=201,
     *     description="Created"
     * ),
     * @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="score",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="comment",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="film_id",
     *                 type="int"
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="int"
     *             )
     *         )
     *     )
     * )
     * )
     */

   public function post(Request $request)
   {
       try {
              $critic = new Critic();
              $critic->user_id = $request->user()->id; 
              $critic->film_id = $request->film_id;
              $critic->score = $request->score;
              $critic->comment = $request->comment;
              $critic->save();
    
              return response()->json(['success' => true, 'message' => 'Critic submitted successfully.']);
       } catch (\Exception $e) {
           return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        
       }
   }
}
