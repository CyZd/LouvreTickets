<?php
// scr/BankCall/BankCaller
namespace App\BankCall;

use Stripe\Stripe;

class BankCaller
{
    private $isSuccess;

    public function sendPayment($totalPrice)
    {
        \Stripe\Stripe::setApiKey("sk_test_UyY88AsxgBLXgp0sWFsZm8gn");

        try {
            $charge=\Stripe\Charge::create([
            'amount'=> $totalPrice*100,
            'currency'=>'eur',
            'source'=>'tok_visa'
            ]);
            $this->setSuccess();
        } catch (\Stripe\Error\Card $e) {
            $this->setFailure();
        }
    }

    public function paymentSuccess()
    {
        return $this->isSuccess;
    }

    public function setSuccess()
    {
        $this->isSuccess=1;
    }

    public function setFailure()
    {
        $this->isSuccess=0;
    }
}
