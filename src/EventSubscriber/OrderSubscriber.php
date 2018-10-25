<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Event\OrderEvent;
use App\PriceCheck\PriceChecker;

class OrderSubscriber implements EventSubscriberInterface
{
    private $checker; 

    public function __construct(PriceChecker $checker)
    {
        $this->checker=$checker;
    }

    public static function getSubscribedEvents()
    {
        return array(
            OrderEvent::REGISTER=>'onFormRegister',
            OrderEvent::GOTOPAYMENT=>'onFormPayment',
        );
    }

    public function onFormRegister(OrderEvent $event)
    {
        $order=$event->getOrder();
        $this->checker->checkPrices($order);
    }

    public function onFormPayment(OrderEvent $event)
    {
        $order=$event->getOrder();
        $this->checker->setFullPrice($order);
    }


}

?>