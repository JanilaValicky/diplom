<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;

class PaymentRepository
{
    public function create($data)
    {
        $payment = new Payment();
        $payment->status = PaymentStatusEnum::Pending;
        $payment->fill($data);
        $payment->save();

        return $payment;
    }

    public function find($id)
    {
        return Payment::findOrFail($id);
    }

    public function findByPaymentIntentId($payment_intent_id): ?Payment
    {
        return Payment::where('payment_intent', $payment_intent_id)->first();
    }
}
