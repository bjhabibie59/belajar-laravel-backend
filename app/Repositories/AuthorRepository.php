<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository extends BaseRepository
{
    public function __construct(Author $model)
    {
        parent::__construct($model);
    }

    public function searchByName(string $name)
    {
        return $this->model->where('name', 'like', '%' . $name . '%')->get();
    }
}
