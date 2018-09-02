<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TicketType;
use App\Form\CommandType;

use Twig\Environment;



use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesController extends Controller
{
    
    public function index(Environment $twig)
    {
        return new Response($twig->render('firstTest.html.twig'));
    }

    public function addTicketTest(Environment $twig)
    {
        // $priceTag=$this->getDoctrine()->getManager()->getRepository(Categories::class)->findOneBy(array('Tarif' => 'normal'));



        // $tickTest=new Tickets;
        // $tickTest->setDate(New \DateTime('now'));
        // $tickTest->setDayType("1");
        // $tickTest->setPriceTag($priceTag->getPricing());
        // $tickTest->setVisitorName("Grobert");
        // $tickTest->setVisitorSurName("Bouligneux");
        // $tickTest->setByerNumber("1");
        // $tickTest->setBatchNumber("1");

        // $entityManager=$this->getDoctrine()->getManager();
        // $entityManager->persist($tickTest);
        // $entityManager->persist($priceTag);
        // $entityManager->flush();

        // $ticketsRepo=$entityManager->getRepository(Tickets::class);
        // $ticketList=$ticketsRepo->findAll();

        return new Response($twig->render('paymentList.html.twig', array('ticketList'=>$ticketList)));
    }

    public function formBuildTest(Request $request, Environment $twig)
    {

        $ticket=new Tickets;
        $order=new Command;

        $ticket->setDate(New \DateTime('now'));

        //$form=$this->createForm(TicketType::class,$ticket);
        

        //$order->addTicketsOrdered($ticket);
        $order->setName('test');

        $form=$this->createForm(CommandType::class,$order);

        
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
            $purchaseStart=$form->getData();
            
            $ageValue=$ticket->getVisitorDoB()->format('Y');
            $price=$this->checkPrices($ageValue, $ticket);
            
            $ticket->setPriceTag($price);

            
            $entityManager=$this->getDoctrine()->getManager();
            
            $entityManager->persist($order);
            $entityManager->persist($ticket);
            

            
            $entityManager->flush();


            $ticketsRepo=$entityManager->getRepository(Tickets::class);
            $ticketList=$ticketsRepo->findAll();

            return new Response($twig->render('paymentList.html.twig', array('ticketList'=>$ticketList)));
            }
        }
        return $this->render('formTest.html.twig', array('form'=>$form->createView()));
        
    }
 
    public function paymentMade(Environment $twig, $slug)
    {   
        return new Response($twig->render('paymentMade.html.twig', array('slug'=>$slug)));
    }

    
    public function paymentFail(Environment $twig, $slug)
    {
        return new Response($twig->render('paymentFail.html.twig', array('slug'=>$slug)));
    }

    public function paymentList(Environment $twig)
    {
        $entityManager=$this->getDoctrine()->getManager();
        $ticketsRepo=$entityManager->getRepository(Tickets::class);
        $ticketList=$ticketsRepo->findAll();
        return new Response($twig->render('paymentList.html.twig', array('ticketList'=>$ticketList)));
    }

    public function checkPrices(int $value, $ticket): float
    {
        
        $currentDate=date('Y');
        $age=$currentDate-$value;

        if($ticket->getReducedPrice())
            {
                $age=200;
            }

        $pricesRange=$this->getDoctrine()->getManager()->getRepository(Categories::class)->findAll();

        foreach($pricesRange as $price){
            $lowEnd=$price->getLowValue();
            $highEnd=$price->getHighValue();
            $correctPrice=$price->getPricing();

            if ($lowEnd <= $age && $age <= $highEnd){
                return $correctPrice;
            }
        }
    }


}