<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\ServiceException;
use App\Models\Plan;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Carbon\Carbon;

class PaymentApiController extends AbstractApiController
{
    private PaymentService $paymentService;

    private SubscriptionService $subscriptionService;

    public function __construct(PaymentService $paymentService, SubscriptionService $subscriptionService)
    {
        $this->paymentService = $paymentService;
        $this->subscriptionService = $subscriptionService;
    }

    public function checkout(string $plan_name): JsonResponse
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $plan = Plan::where('name', $plan_name)->firstOrFail();

            $paymentIntent = PaymentIntent::create([
                'amount' => $plan->price * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $paymentData = [
                'amount' => $plan->price,
                'payment_intent' => $paymentIntent->id,
            ];

            $payment = $this->paymentService->createPayment($paymentData);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return response()->json($output);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function success(Request $request): JsonResponse
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $payment_intent_id = $request->get('payment_intent');
            $payment = $this->paymentService->findPaymentByIntentId($payment_intent_id['id']);

            if (!$payment) {
                throw new ServiceException('Payment not found');
            }

            if ($payment->status === PaymentStatusEnum::Pending) {
                $payment = $this->paymentService->updatePaymentStatus($payment->id, PaymentStatusEnum::Succeeded);
            }

            $plan = Plan::where('price', $payment->amount)->firstOrFail();

            $subscriptionData = [
                'payment_id' => $payment->id,
                'plan_id' => $plan->id,
                'end_date' => Carbon::now()->addMonth(),
            ];

            $subscription = $this->subscriptionService->create($subscriptionData);

            return response()->json(['message' => 'Thanks for subscribing, the payment was successful!']);
        } catch (Exception $e) {
            $payment = $this->paymentService->updatePaymentStatus($payment->id, PaymentStatusEnum::Failed);
            throw new ServiceException($e->getMessage(), intval($e->getCode()));
        }
    }
}
