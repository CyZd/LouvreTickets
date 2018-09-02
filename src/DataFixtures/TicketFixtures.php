<?php
namespace App\DataFixtures;

use App\Entity\Tickets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // for($i=0;$i<998;$i++){
        //     $ticket=new Tickets();

        // }
    }
}

?>