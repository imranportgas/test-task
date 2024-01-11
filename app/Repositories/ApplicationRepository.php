<?php

namespace App\Repositories;

use App\Models\Application;
use App\Repositories\Interface\BaseCRUDRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApplicationRepository implements BaseCRUDRepositoryInterface
{
    public function getAll(): Collection
    {
        return Application::all();
    }

    public function getById(int $id)
    {
        return Application::query()->find($id);
    }


}
