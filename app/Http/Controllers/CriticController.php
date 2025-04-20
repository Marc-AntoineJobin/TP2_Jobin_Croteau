<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\CriticRepositoryInterface;
use App\Repository\Eloquent\CriticRepository;
use App\Http\Resources\CriticResource;

class CriticController extends Controller
{
    private CriticRepositoryInterface $criticRepository;

    public function __construct(CriticRepositoryInterface $criticRepository)
    {
        $this->criticRepository = $criticRepository;
    }
    /**
     * @OA\Post(
     * path="/api/critics",
     * tags={"critics"},
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

   public function create(Request $request)
   {
    try
    {
        $film = $this->criticRepository->create($request->all());
        return (new CriticResource($film))->response()->setStatusCode(CREATED);
    }
    catch(Exception $ex)
    {
        abort(SERVER_ERROR, $ex->getMessage());
    }  
   }
}
