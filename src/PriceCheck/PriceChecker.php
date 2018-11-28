<?php
//src/PriceCheck/PriceCHecker

namespace App\PriceCheck;

use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;
use App\Repository\CategoryRepository;

class PriceChecker
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager=$entityManager;
        $this->categoryRepository=$categoryRepository;
    }

    public function checkPrices(Command $order)
    {
        $currentDate=date('Y');
        $pricesRange=$this->entityManager->getRepository(Category::class)->findAll();

        $ticketList=$order->getTicketsOrdered();
        foreach ($ticketList as $element) {
            $birthDate=$element->getVisitorDoB()->format('Y');
            $age=$currentDate-$birthDate;

            if ($element->getReducedPrice()) {
                $age=200;
            }

            foreach ($pricesRange as $price) {
                $lowEnd=$price->getLowValue();
                $highEnd=$price->getHighValue();
                $correctPrice=$price->getPricing();

                if ($lowEnd <= $age && $age <= $highEnd) {
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
        foreach ($list as $element) {
            $totalPrice+=$element->getPriceTag();
        }
        $order->setTotalPrice($totalPrice);
    }
}
