<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;


class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct($model)     
    {         
        $this->model = new $model;
    }

    /**
    * @param array $attributes
    *
    * @return Model
    */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
 
    /**
    * @param $id
    * @return Model
    */
    public function getById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function getAll(int $perPage = 0) 
    {
        if($perPage > 0)
        {
            return $this->model->paginate($perPage);
        }
        else
        {
            return $this->model->all();
        }
    }

    public function update(int $id,array $content)
    {
    $item = $this->model->find($id);
    
    if ($item) {
        $item->update($content);
        return $item;
    }
    
    return false;
    }
    public function delete(int $id)
    {
        $item = $this->model->findOrFail($id);  
        $item->delete();  
    }


}

?>