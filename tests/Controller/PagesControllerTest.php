<?php
namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use App\Controller\PagesController;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;
use App\Form\TicketType;
use App\Form\CommandType;
use Stripe\Stripe;



class PagesControllerTest extends WebTestCase
{

    //renvoie une 500!?
    public function testIndex()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testToken()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $session = new Session(new MockArraySessionStorage());
        $client->getContainer()->set('session', $session);
        $client->request('GET', '/');
        $this->assertContains('orderToken',$session);
    }


    public function testForm()
    {
        $client = static::createClient();
        $crawler=$client->request('GET', '/formtest/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testPaymentList()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $crawler=$client->request('GET', '/payment_list/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testPaymentNotFound()
    {
        $client = static::createClient(array('http://localhost:8000/', 8000));
        $crawler=$client->request('GET', '/executePayment/');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
    
}

?>