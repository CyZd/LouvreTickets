<?php
namespace App\Tests\Controller;
use App\Controller\PagesController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use App\Entity\Tickets;
use App\Entity\Command;


class testSendMailTickets extends WebTestCase
{

    public function testMailSent()
    {
        $mockOrder=new Command;
        $mockTicket=new Tickets;

        $mockOrder->addTicketsOrdered($mockTicket);
        $mockOrder->setId(5);
        $mockOrder->setVisitorEmail('sylvain.duval29@hotmail.fr');

        $client = $client=static::createClient();

        $client->enableProfiler();

        $crawler = $client->request('POST', 'App\Mailer\Mailer::sendMailTickets()');

        $mailCollector=$client->getProfile()->getCollector('swiftmailer');

        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages=$mailCollector->getMessages();
        $message=$collectedMessages[0];


        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Musée du louvre: votre commande est arrivée', $message->getSubject());
        $this->assertSame('louvre-musee-reservation@louvre.gouv.fr', key($message->getFrom()));
        $this->assertSame('sylvain.duval29@hotmail.fr', key($message->getTo()));
    }
}
?>