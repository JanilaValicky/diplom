<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Services\BrandService;
use Exception;
use Illuminate\Http\Request;

class BrandApiController extends AbstractApiController
{
    private BrandService $service;

    public function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    public function store(StoreBrandRequest $request)
    {
        try {
            return $this->createResponse($this->service->create($request->all()));
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function show(Request $request)
    {
        try {
            return $this->createResponse($this->service->get($request->all()));
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function brandNameExists(Request $request)
    {
        try {
            return $this->createResponse([
                'exists' => $this->service->brandExists($request->name),
            ]);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}
