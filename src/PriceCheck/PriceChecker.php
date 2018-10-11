<?
//src/PriceCheck/PriceCHecker

namespace App\PriceCheck;

use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;

class PriceChecker
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    public function checkPrices(Command $order)
    {
        $currentDate=date('Y');

        $ticketList=$order->getTicketsOrdered();
        foreach($ticketList as $element)
            {
                $birthDate=$element->getVisitorDoB()->format('Y');
                $age=$currentDate-$birthDate;

                if($element->getReducedPrice())
                {
                    $age=200;
                }

                $pricesRange=$this->entityManager->getRepository(Categories::class)->findAll();
                foreach($pricesRange as $price)
                {
                    $lowEnd=$price->getLowValue();
                    $highEnd=$price->getHighValue();
                    $correctPrice=$price->getPricing();

                    if ($lowEnd <= $age && $age <= $highEnd){
                        $element->setPriceTag($correctPrice);
                        $this->entityManager->persist($element);
                    }
                }
            }
        
    }

    public function setFullPrice(Command $order)
    {
        $list=$order->getTicketsOrdered();
        $totalPrice=0;
        foreach($list as $element)
            {
                $totalPrice+=$element->getPriceTag();
            }
        $order->setTotalPrice($totalPrice);
    }
}
?>