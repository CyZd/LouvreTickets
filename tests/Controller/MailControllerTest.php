<?php
namespace App\Tests\Controller;

use App\Controller\PagesController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Repository\TicketsRepository;
use App\Repository\CommandRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Mailer\Mailer;



class testSendMailTickets extends WebTestCase
{
    public function testMailSent()
    {
        $mockOrder=new Command;
        $mockTicket=new Tickets;

        $mockTicket=new Tickets;
        $mockTicket->setVisitorDoB(new \Datetime('05/23/2014'));
        $mockTicket->setReducedPrice(false);

        $mockOrder->addTicketsOrdered($mockTicket);
        $mockOrder->setId(2140);
        $mockOrder->setVisitorEmail('sylvain.duval29@hotmail.fr');

        $client = static::createClient(array('http://localhost:8000/', 8000));
        $client->followRedirects();
        $client->enableProfiler();

        $session=$client->getContainer()->get('session');
        $session->set('orderToken', $mockOrder->getId());
        $session->start();
        $session->save();

        $crawler = $client->request('POST', '/fr/mail_order/');
        
        $mailCollector=$client->getProfile()->getCollector('swiftmailer');

        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages=$mailCollector->getMessages();
        $message=$mailCollector->getMessageCount();
        $message=$collectedMessages[0];


        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Musée du louvre: votre commande est arrivée', $message->getSubject());
        $this->assertSame('louvre-musee-reservation@louvre.gouv.fr', key($message->getFrom()));
        $this->assertSame('sylvain.duval29@hotmail.fr', key($message->getTo()));
    }
}
