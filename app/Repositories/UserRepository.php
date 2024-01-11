<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\BaseCRUDRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements BaseCRUDRepositoryInterface
{

    public function getAll(): Collection
    {
        return User::all();
    }

    public function getById(int $id)
    {
        return User::query()->find($id);
    }


}
