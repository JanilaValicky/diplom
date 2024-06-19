<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Services\FormService;
use Exception;
use Illuminate\Http\Request;

class FormApiController extends AbstractApiController
{
    private FormService $service;

    public function __construct(FormService $service)
    {
        $this->middleware('users.form')->except(['index', 'store']);
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->service->getByParamsPaginated($request->user->id, $request->all());

            return $this->createResponse($data);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $data = $this->service->get($request->user->id, $id);

            return $this->createResponse($data);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function store(StoreFormRequest $request)
    {
        try {
            $data = $this->service->create($request);

            return $this->createResponse($data);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $data = $this->service->update($request, $id);

            return $this->createResponse($data);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $data = $this->service->delete($request->user->id, $id);

            return $this->createResponse($data);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}
