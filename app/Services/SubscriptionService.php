<?php

namespace App\Services;

use App\Enums\SubscriptionStatusEnum;
use App\Http\Resources\SubscriptionResource;
use App\Repositories\SubscriptionRepository;

class SubscriptionService
{
    private SubscriptionRepository $repository;

    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $subscriptionData): SubscriptionResource
    {
        $subscription = $this->repository->create($subscriptionData);
        $this->repository->updateStatus($subscription->id, SubscriptionStatusEnum::Active);

        return new SubscriptionResource($subscription);
    }

    public function get(array $request)
    {
        return new SubscriptionResource($this->repository->getByUserId($request['user']['id']));
    }

    public function updateStatus($id, $status)
    {
        return new SubscriptionResource($this->repository->updateStatus($id, $status));
    }
}
