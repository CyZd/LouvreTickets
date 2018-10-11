<?php
namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use App\Controller\PagesController;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;
use App\Form\TicketType;
use App\Form\CommandType;
use Stripe\Stripe;

use App\PriceCheck\PriceChecker;

use App\Repository\TicketsRepository;
use App\Repository\CommandRepository;
use App\Repository\CategoriesRepository;

use Doctrine\ORM\EntityManagerInterface;




class PagesControllerTest extends WebTestCase
{
//routes and token test
    public function testIndex()
    {
        $client = static::createClient(array('http://localhost/', 8000));
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testToken()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $session= $client->getContainer()->get('session');
        $session->start();
        $client->request('GET', '/');
        $sessionData=$session->all();
        $testString='orderToken';
        $this->assertTrue(array_key_exists($testString,$sessionData));
    }

    public function testForm()
    {
        $client = static::createClient();
        $crawler=$client->request('GET', '/formtest/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testPaymentNotFound()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $crawler=$client->request('GET', '/executePayment/');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

//secondary functions test with mock repo-warning-mock repo is empty
    public function testRecapOrder()
    {
        $order=new Command();
        $order->setId((int)12345);
        $order->setName('testrand');
        $orderId=$order->getId();


        $client = static::createClient(array('http://localhost:8000/', 8000));
        $session= $client->getContainer()->get('session');
        $session->start();
        $client->request('GET', '/');
        $session->set('orderToken', $orderId);
        $sessionData=$session->all();

        $orderRepo=$this->createMock(CommandRepository::class);
        $orderRepo->expects($this->any())
            ->method('find')
            ->willReturn($order);

        
        $testOrder=$orderRepo->find($orderId);
        $testId=$testOrder->getId();
        $sessionData=$session->all();

        $this->assertEquals($orderId, $testId);
        $this->assertTrue(in_array($orderId,$sessionData));

    }

    public function testExecutePayment()
    {
        $order=new Command();
        $order->setId((int)12345);
        $order->setName('testrand');
        $orderId=$order->getId();

        $ticketOne=new Tickets();
        $ticketOne->setPriceTag(32);

        $ticketTwo=new Tickets();
        $ticketTwo->setPriceTag(20);

        $order->addTicketsOrdered($ticketOne);
        $order->addTicketsOrdered($ticketTwo);

        $client = static::createClient(array('http://localhost:8000/', 8000));
        $session= $client->getContainer()->get('session');
        $session->start();
        $client->request('GET', '/');
        $session->set('orderToken', $orderId);
        $sessionData=$session->all();

        $orderRepo=$this->createMock(CommandRepository::class);
        $orderRepo->expects($this->any())
            ->method('find')
            ->willReturn($order);

        
        $testOrder=$orderRepo->find($orderId);
        $testId=$testOrder->getId();

        $list=$order->getTicketsOrdered();
        $price=0;
        foreach($list as $element)
        {
            $price+=$element->getPriceTag();
        }

        $this->assertEquals($price, 52);
    }

    public function testCheckPrices()
    {
        $catChild=new Categories();
        $catChild->setPricing(10);
        $catChild->setLowValue(0);
        $catChild->setHighValue(15);


        $categoriesRepo=$this->createMock(CategoriesRepository::class);
        $categoriesRepo->expects($this->any())
            ->method('find')
            ->willReturn($catChild);

        $ticketChild=new Tickets;
        $ticketChild->setVisitorDoB(new \Datetime('23-05-2014'));
        $ticketChild->setReducedPrice(false);

        $order=new Command();
        $order->addTicketsOrdered($ticketChild);

        $priceCheck=new PriceChecker(new EntityManagerInterface);
        $result=$priceCheck->checkPrices($order);

        $this->assertEquals($result, 10);

    }
    

    

}

?>