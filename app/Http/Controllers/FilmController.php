<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Repository\Eloquent\FilmRepository;
use App\Repository\FilmRepositoryInterface; // hm-hmm
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
    
}

