<?php
// scr/BankCall/BankCaller
namespace App\BankCall;

use Psr\Log\LoggerInterface;
use Stripe\Stripe;
use Stripe\Charge;

//def $apikey;

//public function __construct(string $apiKey, LoggerInterface $logger)
//{
//  stripe/appeller la clé à cet endroit, et mettre la clé dans env.dist
//$this->logger=$logger;
//}

class BankCaller
{
    private $isSuccess;
    private $logger;

    public function __construct(string $apiKey, LoggerInterface $logger)
    {
        $this->logger = $logger;

        Stripe::setApiKey($apiKey);
    }

    public function sendPayment($totalPrice)
    {
        $parameters = [
            'amount'=> $totalPrice*100,
            'currency'=>'eur',
            'source'=>'tok_visa'
        ];

        try {
            Charge::create([
                $parameters
            ]);
            $this->setSuccess();
        } catch (\Stripe\Error\Card $e) {
            $this->setFailure();
            $this->logger->warning('Payment failed', $parameters);
        }
    }

    public function paymentSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function setSuccess()
    {
        $this->isSuccess=true;
    }

    public function setFailure()
    {
        $this->isSuccess=false;
    }
}
