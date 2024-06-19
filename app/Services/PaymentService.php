<?php

namespace App\Services;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\ServiceException;
use App\Models\Payment;
use App\Repositories\PaymentRepository;

class PaymentService
{
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function createPayment($data)
    {
        $data['status'] = PaymentStatusEnum::Pending;

        return $this->paymentRepository->create($data);
    }

    public function updatePaymentStatus($paymentId, $status)
    {
        $payment = $this->paymentRepository->find($paymentId);
        if (!$payment) {
            throw new ServiceException('Payment not found');
        }

        $payment->status = $status;
        $payment->save();

        return $payment;
    }

    public function findPaymentByIntentId(string $payment_intent_id): ?Payment
    {
        return $this->paymentRepository->findByPaymentIntentId($payment_intent_id);
    }
}
