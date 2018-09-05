<?php
namespace App\DataFixtures;

use App\Entity\Tickets;
use App\Entity\Command;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<998;$i++){
            $command=new Command();
            $command->setName('test');
            $command->setVisitorEmail('JJFictif@fictif.fr');

            $ticket=new Tickets();
            $ticket->setDate(new \Datetime('03-09-2018'));
            $ticket->setDayType(1);
            $ticket->setPriceTag(16);
            $ticket->setVisitorName('Jean-Jacques');
            $ticket->setVisitorSurName('Fictif');
            $ticket->setDesiredDate(new \Datetime('06-02-2019'));
            $ticket->setVisitorCountry('FR');
            $ticket->setVisitorDoB(new \Datetime('21-06-1983'));
            $ticket->setReducedPrice(0);
            $ticket->setCommand($command);
            
            $manager->persist($command);
            $manager->persist($ticket);
        }
        $manager->flush();
    }
}

?>