<?php

namespace App\Tests\Form;

use App\Form\Type\TestedType;
use App\Model\TestObject;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;
use App\Form\TicketType;
use App\Form\CommandType;
use Stripe\Stripe;



class CommandTypeTest extends TypeTestCase
{    


    //form test
    public function testCommandType()
    {
        $mockOrder=new Command;
        $mockTicket=new Tickets;

        $mockTicket->setDate(New \DateTime('now'));
        

        $mockOrder->addTicketsOrdered($mockTicket);
        $mockOrder->setName(uniqid(rand()));

        $form=$this->factory->create(CommandType::class, $mockOrder);

        
        $form->setValues(array(
            'command[VisitorEmail]'=>'sylvain.duval29@hotmail.fr',
        ));

        $values=$form->getPhpValues();

        $values['command']['ticketsOrdered'][0]['VisitorName']='UnPrénomVisiteur';
        $values['command']['ticketsOrdered'][0]['VisitorSurName']='UnNomVisiteur';
        $values['command']['ticketsOrdered'][0]['VisitorCountry']='AF';
        $values['command']['ticketsOrdered'][0]['VisitorDoB']['day']='13';
        $values['command']['ticketsOrdered'][0]['VisitorDoB']['month']='0002';
        $values['command']['ticketsOrdered'][0]['VisitorDoB']['year']='1984';
        $values['command']['ticketsOrdered'][0]['DesiredDate']['day']='22';
        $values['command']['ticketsOrdered'][0]['DesiredDate']['month']='0004';
        $values['command']['ticketsOrdered'][0]['DesiredDate']['year']='2019';
        $values['command']['ticketsOrdered'][0]['DayType']->select(1);


        $form->submit($values);
        $client->followRedirect();
        
        $view=$form->createView();
        $children=$view->children;

        foreach(array_keys($values) as $key){
            $this->assertArrayHasKey($key, $children);
        }

    }
}
?>