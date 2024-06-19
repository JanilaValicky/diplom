<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function getAll(): Collection;

    public function create(array $details);

    public function get($id);

    public function update($id, array $details);

    public function delete($id);
}
