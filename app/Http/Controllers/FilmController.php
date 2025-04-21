<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Repository\Eloquent\FilmRepository;
use App\Repository\FilmRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use Exception;


class FilmController extends Controller
{
    private FilmRepositoryInterface $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }
    /**
     * @OA\Post(
     *     path="/api/films",
     *     summary="create a film",
     *  security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="release_year",
     *                     type="year",
     *                 ),
     *                 @OA\Property(
     *                     property="length",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="special_features",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="language_id",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="film created successfully",
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
    public function create(Request $request)
    {
        try {
            $film = $this->filmRepository->create($request->all());
            return (new FilmResource($film))->response()->setStatusCode(CREATED);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }
    /**
     * @OA\Put(
     *     path="/api/films",
     *     summary="update a film",
     *  security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="release_year",
     *                     type="year",
     *                 ),
     *                 @OA\Property(
     *                     property="length",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="special_features",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="language_id",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="film updated successfully",
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
    public function update(Request $request, $id)
    {
        try {
            $film = $this->filmRepository->update($id, $request->all());
            return (new FilmResource($film))->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/films/{id}",
     *     summary="delete a film",
     *  security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="film deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function delete($id)
    {
        try {
            $this->filmRepository->delete($id);
            return response()->json(['message' => 'Film deleted successfully'], 200);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, $ex->getMessage());
        }
    }

}

