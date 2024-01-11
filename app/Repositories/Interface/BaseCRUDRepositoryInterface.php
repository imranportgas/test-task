<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface BaseCRUDRepositoryInterface
{
    public function getAll() : Collection;
    public function getById(int $id);
}
