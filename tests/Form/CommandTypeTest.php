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
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class CommandTypeTest extends TypeTestCase
{    

    private $validator;
    //form test
    public function testCommandType()
    {
        $datas=array(
            'VisitorEmail'=>'test@test.fr',
        );

        $mockOrder=new Command;
        $mockOrder->setId(1);

        $form=$this->factory->create(CommandType::class, $mockOrder);

        $referenceOrder=new Command;
        $referenceOrder->setId(2);

        $referenceOrder->setVisitorEmail('test@test.fr');

        $form->submit($datas);
        
        $view=$form->createView();
        $children=$view->children;

        foreach(array_keys($datas) as $key){
            $this->assertArrayHasKey($key, $children);
        }

    }

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator),
        );
    }

    
}
?>