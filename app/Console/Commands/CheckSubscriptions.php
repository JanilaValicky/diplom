<?php

use App\Enums\SubscriptionStatusEnum;
use App\Repositories\SubscriptionRepository;
use Illuminate\Console\Command;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';

    protected $description = 'Check and update subscription statuses';

    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        parent::__construct();
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function handle()
    {
        $subscriptions = $this->subscriptionRepository->getExpiringSubscriptions();

        foreach ($subscriptions as $subscription) {
            if ($subscription->end_date->isPast()) {
                $this->subscriptionRepository->updateStatus($subscription->id, SubscriptionStatusEnum::Paused);
            }
        }

        $this->info('Subscription statuses checked and updated.');
    }
}
