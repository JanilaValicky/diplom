<?php

namespace App\Services;

use App\Http\Resources\BrandResource;
use App\Repositories\BrandRepository;

class BrandService
{
    private BrandRepository $repository;

    public function __construct(BrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $request)
    {
        return new BrandResource($this->repository->create($request));
    }

    public function get(array $request)
    {
        return new BrandResource($this->repository->getByUserId($request['user']['id']));
    }

    public function getBrandName(int $user_id)
    {
        $brand = $this->repository->getByUserId($user_id);

        return is_null($brand) ? null : $brand->name;
    }

    public function brandExists(string $name): bool
    {
        return !is_null($this->repository->getByName($name));
    }
}
