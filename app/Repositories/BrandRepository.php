<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Support\Collection;

class BrandRepository implements BaseRepositoryInterface
{
    public function getAll(): Collection
    {
        return Brand::all();
    }

    public function create(array $details)
    {
        return Brand::create($details);
    }

    public function get($id): Brand
    {
        return Brand::findOrFail($id);
    }

    public function update($id, array $details): Brand
    {
        $brand = Brand::findOrFail($id);
        $brand->update($details);

        return $brand;
    }

    public function delete($id): int
    {
        return Brand::destroy($id);
    }

    public function getByUserId($user_id): ?Brand
    {
        return Brand::where('user_id', $user_id)->first();
    }

    public function getByName(string $name): ?Brand
    {
        return Brand::where('name', $name)->first();
    }
}
