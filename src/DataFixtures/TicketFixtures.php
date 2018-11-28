<?php
namespace App\DataFixtures;

use App\Entity\Ticket;
use App\Entity\Command;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<998;$i++) {
            $command=new Command();
            $command->setName('test');
            $command->setVisitorEmail('JJFictif@fictif.fr');
            $command->setDayType(1);
            $command->setHasBeenPaid(true);
            $command->setDesiredDate(new \DateTime('06-02-2019'));
            $command->setDate(new \DateTime('03-09-2018'));

            $ticket=new Ticket();
            $ticket->setDate(new \DateTime('03-09-2018'));
            $ticket->setPriceTag(16);
            $ticket->setVisitorName('Jean-Jacques');
            $ticket->setVisitorSurName('Fictif');
            $ticket->setVisitorCountry('FR');
            $ticket->setVisitorDoB(new \DateTime('21-06-1983'));
            $ticket->setReducedPrice(false);
            $ticket->setCommand($command);

            $manager->persist($command);
            $manager->persist($ticket);
        }

        $normal=new Category();
        $normal->setTarif('normal');
        $normal->setPricing(16);
        $normal->setLowValue(12);
        $normal->setHighValue(59);
        $manager->persist($normal);

        $enfant=new Category();
        $enfant->setTarif('enfant');
        $enfant->setPricing(8);
        $enfant->setLowValue(4);
        $enfant->setHighValue(11);
        $manager->persist($enfant);

        $senior=new Category();
        $senior->setTarif('senior');
        $senior->setPricing(12);
        $senior->setLowValue(60);
        $senior->setHighValue(130);
        $manager->persist($senior);

        $gratuit=new Category();
        $gratuit->setTarif('gratuit');
        $gratuit->setPricing(0);
        $gratuit->setLowValue(0);
        $gratuit->setHighValue(3);
        $manager->persist($gratuit);

        $reduit=new Category();
        $reduit->setTarif('reduit');
        $reduit->setPricing(10);
        $reduit->setLowValue(199);
        $reduit->setHighValue(201);
        $manager->persist($reduit);

        $manager->flush();
    }
}
