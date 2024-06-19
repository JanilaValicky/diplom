<?php

namespace App\Services;

use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Http\Resources\Form\FormCollection;
use App\Http\Resources\Form\FormResource;
use App\Repositories\FormRepository;
use Hashids\Hashids;

class FormService
{
    private FormRepository $repository;

    private Hashids $hashids;

    public function __construct(FormRepository $repository)
    {
        $this->repository = $repository;
        $this->hashids = new Hashids();
    }

    public function getAll(): FormCollection
    {
        return new FormCollection($this->repository->getAll());
    }

    public function create(StoreFormRequest $request): FormResource
    {
        return new FormResource($this->repository->create($request->toArray()));
    }

    public function get(int $user_id, string $id): FormResource
    {
        $form_id = $this->hashids->decode($id)[0];

        return new FormResource($this->repository->getByIdAndUserId($form_id, $user_id));
    }

    public function update(UpdateFormRequest $request, string $id): FormResource
    {
        $form_id = $this->hashids->decode($id)[0];

        return new FormResource($this->repository->update($form_id, $request->toArray()));
    }

    public function delete(int $user_id, string $id)
    {
        $form_id = $this->hashids->decode($id)[0];

        return $this->repository->delete($form_id);
    }

    public function getByUserId(int $user_id): FormCollection
    {
        return new FormCollection($this->repository->getByUserId($user_id));
    }

    public function getByParamsPaginated(int $user_id, array $params)
    {
        $data = $this->repository->getByParamsPaginated($user_id, $params);

        return FormResource::collection($data)->response()->getData();
    }
}
