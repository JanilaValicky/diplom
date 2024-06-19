<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subscription_plan' => $this->subscription_plan,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'start_date' => $this->start_date->format(Subscription::HUMAN_DATE_TIME_FORMAT),
            'expires_at' => $this->expires_at->format(Subscription::HUMAN_DATE_TIME_FORMAT),
        ];
    }
}
