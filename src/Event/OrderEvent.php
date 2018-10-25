<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Command;

class OrderEvent extends Event
{
    protected $order;

    const REGISTER='order.registered';
    const GOTOPAYMENT='order.payment';

    public function __construct(Command $order)
    {
        $this->order=$order;
    }

    public function getOrder(): ?Command
    {
        return $this->order;
    }

    public function setOrder(Command $order):self
    {
        $this->order=$order;
        return $this;
    }
}
?>