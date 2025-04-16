<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Repository\FilmRepositoryInterface;
use App\Models\Film;

class FilmRepository extends BaseRepository implements FilmRepositoryInterface
{
    public function __construct(Film $model)
    {
        parent::__construct($model);
    }


}