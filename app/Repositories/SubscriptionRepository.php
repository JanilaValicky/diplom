<?php

namespace App\Repositories;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Subscription;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SubscriptionRepository implements BaseRepositoryInterface
{
    public function getAll(): Collection
    {
        return Subscription::all();
    }

    public function create(array $details)
    {
        return Subscription::create($details);
    }

    public function get($id): Model
    {
        return Subscription::findOrFail($id);
    }

    public function update($id, array $details): Model
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($details);

        return $subscription;
    }

    public function delete($id): int
    {
        return Subscription::destroy($id);
    }

    public function getByUserId($user_id): ?Model
    {
        return Subscription::where('user_id', $user_id)->first();
    }

    public function updateStatus($id, $status)
    {
        $subscription = $this->get($id);
        $subscription->status = $status;
        $subscription->save();

        return $subscription;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Subscription[]
     */
    public function getExpiringSubscriptions()
    {
        return Subscription::where('status', SubscriptionStatusEnum::Active)
            ->where('end_date', '<', now())
            ->get();
    }
}
