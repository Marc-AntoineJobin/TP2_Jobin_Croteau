<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Repository\Eloquent\FilmRepository;
use App\Repository\FilmRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;


class FilmController extends Controller
{   
    private FilmRepositoryInterface $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }
    
    public function create(Request $request)
    {
        try
        {
            $film = $this->filmRepository->create($request->all());
            return (new FilmResource($film))->response()->setStatusCode(CREATED);
        }
        
        catch(Exception $ex)
        {
            abort(SERVER_ERROR, 'Server error');
        }       
    }

    public function update(Request $request, $id)
    {
        try
        {
            $film = $this->filmRepository->update($id, $request->all());
            return (new FilmResource($film))->response()->setStatusCode(OK);
        }
        
        catch(Exception $ex)
        {
            abort(SERVER_ERROR, 'Server error');
        }       
    }

    public function delete($id)
    {
        try
        {
            $this->filmRepository->delete($id);
            return response()->json(['message' => 'Film deleted successfully'], 200);
        }
        
        catch(Exception $ex)
        {
            abort(SERVER_ERROR, 'Server error');
        }       
    }
    
}

