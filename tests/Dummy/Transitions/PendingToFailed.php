<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Transition;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

class PendingToFailed extends Transition
{
    /** @var \Spatie\ModelStates\Tests\Dummy\Payment */
    private $payment;

    /** @var string */
    private $message;

    public function __construct(Payment $payment, string $message)
    {
        $this->payment = $payment;
        $this->message = $message;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->isOneOf(Pending::class);
    }

    public function handle(): Payment
    {
        $this->payment->state = new Failed($this->payment);
        $this->payment->failed_at = now();
        $this->payment->error_message = $this->message;

        $this->payment->save();

        return $this->payment;
    }
}
