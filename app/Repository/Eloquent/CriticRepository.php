<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Repository\CriticRepositoryInterface;
use App\Models\Critic;

class CriticRepository extends BaseRepository implements CriticRepositoryInterface
{
    public function __construct(Critic $model)
    {
        parent::__construct($model);
    }
}