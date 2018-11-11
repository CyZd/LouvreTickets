<?php
namespace App\Tests\PriceChecker;


use App\Entity\Category;
use App\Entity\Command;
use App\Entity\Ticket;
use App\PriceCheck\PriceChecker;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class PriceCheckTest extends TestCase
{
    private $checkPricer;

    public function setUp()
    {
        $entityManager = $this->prophesize(EntityManagerInterface::class);
        $categoryRepository = $this->prophesize(CategorieRepository::class);

        $category = new Category();
        $category->setHighValue(60);
        $category->setLowValue(12);
        $category->setPricing(16);
        $category->setTarif('Tarif normal');

        $category2 = new Category();
        $category2->setHighValue(201);
        $category2->setLowValue(199);
        $category2->setPricing(8);
        $category2->setTarif('Tarif normal');

        $categoryRepository->findAll()->willReturn([
            $category,
            $category2
        ]);

        $this->checkPricer = new PriceChecker(
            $entityManager->reveal(),
            $categoryRepository->reveal()
        );
    }

    public function testNormalPrice()
    {
        $command = new Command();
        $ticket = new Ticket();
        $ticket->setDate(new \DateTime('2018-11-07'));
        $ticket->setVisitorDoB(new \DateTime('1996-01-01'));
        $command->addTicketsOrdered($ticket);

        $this->checkPricer->checkPrices($command);
        $this->checkPricer->setFullPrice($command);


        $this->assertSame(16.0, $command->getTotalPrice());
    }

    public function testMixte()
    {
        $command = new Command();
        $ticket = new Ticket();
        $ticket->setDate(new \DateTime('2018-11-07'));
        $ticket->setVisitorDoB(new \DateTime('1996-01-01'));
        $command->addTicketsOrdered($ticket);

        $ticket = new Ticket();
        $ticket->setDate(new \DateTime('2018-11-07'));
        $ticket->setVisitorDoB(new \DateTime('1948-01-01'));
        $ticket->setReducedPrice(true);
        $command->addTicketsOrdered($ticket);

        $this->checkPricer->checkPrices($command);
        $this->checkPricer->setFullPrice($command);


        $this->assertSame(24.0, $command->getTotalPrice());
    }
}