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
use Stripe\Stripe;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

use Twig\Environment;



use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PagesController extends Controller
{
    
    public function index(Environment $twig, SessionInterface $session)
    {
        $session = new Session(new NativeSessionStorage(), new AttributeBag());
        $session->set('orderToken', '');
        return new Response($twig->render('firstTest.html.twig'));
    }



    public function formBuildTest(Request $request, Environment $twig, SessionInterface $session)
    {
        
        $ticket=new Tickets;
        $order=new Command;

        $ticket->setDate(New \DateTime('now'));
        

        $order->addTicketsOrdered($ticket);
        $order->setName($this->randCommandName());

        $form=$this->createForm(CommandType::class,$order);

        
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid())
            {

                $purchaseStart=$form->getData();
                
                $entityManager=$this->getDoctrine()->getManager();

                $list=$order->getTicketsOrdered();

                foreach($list as $element)
                {
                    $ageValue=$element->getVisitorDoB()->format('Y');
                    $price=$this->checkPrices($ageValue, $element);
                
                    $element->setPriceTag($price);
                    $entityManager->persist($element);
                }

                $entityManager->persist($order);
                $entityManager->flush();

                $orderId=$order->getId();
                $session->set('orderToken',$orderId);


                // $ticketsRepo=$entityManager->getRepository(Tickets::class);
                // $ticketList=$ticketsRepo->findAll();

                // $this->sendPayment($order);
                // $this->sendMailTickets($order, $ticket)

                return $this->forward('App\Controller\PagesController::recapOrder');
            }
        }
        return $this->render('formTest.html.twig', array('form'=>$form->createView()));

    }

    public function recapOrder(Request $request, Environment $twig, SessionInterface $session)
    {
        $entityManager=$this->getDoctrine()->getManager();
        $orderRepo=$entityManager->getRepository(Command::class);
        $orderId=$session->get('orderToken');

        $currentOrder=$orderRepo->find($orderId);

        $list=$currentOrder->getTicketsOrdered();

        return $this->render('orderRecap.html.twig', array('ticket'=>$list));

    }
 

    public function executePayment(Request $request, Environment $twig, SessionInterface $session)
    {
        $entityManager=$this->getDoctrine()->getManager();
        $orderRepo=$entityManager->getRepository(Command::class);
        $orderId=$session->get('orderToken');
        if($orderId==null)
        {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }

        $currentOrder=$orderRepo->find($orderId);

        $list=$currentOrder->getTicketsOrdered();

        $totalPrice=0;
            foreach($list as $element)
            {
                $totalPrice+=$element->getPriceTag();
            }
        
        $this->sendPayment($totalPrice);
        
        if($this->sendPayment($totalPrice)){
            $this->sendMailTickets($currentOrder, $list);
            return $this->render('paymentMade.html.twig', array('order'=>$currentOrder, 'ticket'=>$list));
        }else{
            $entityManager=$this->getDoctrine()->getManager();
            $orderRepo=$entityManager->getRepository(Command::class);
            $ticketSRepo=$entityManager->getRepository(Tickets::class);
            $orderId=$session->get('orderToken');

            $currentOrder=$orderRepo->find($orderId);

            
            $tickets=$entityManager->findBy(array('commandId'=>$currentOrder->getId()));

            $entityManager->remove($currentOrder);
            $entityManager->remove($tickets);

            return $this->render('paymentFail.html.twig');   
        }     
    }

    

    public function paymentList(Environment $twig)
    {
        $entityManager=$this->getDoctrine()->getManager();
        $ticketsRepo=$entityManager->getRepository(Tickets::class);
        $ticketList=$ticketsRepo->findAll();
        return new Response($twig->render('paymentList.html.twig', array('ticketList'=>$ticketList)));
    }


//secondary functions
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

    public function sendMailTickets(Command $command, $ticket)
    {

        $mailDestination=$command->getVisitorEmail();

        $message=(new \Swift_Message('Musée du louvre: votre commande est arrivée'))
        ->setFrom('louvre-musee-reservation@louvre.gouv.fr')
        ->setTo($mailDestination)
        ->setBody(
            $this->renderView(
                'emails/registration.html.twig',
                array(
                    'ticket'=>$ticket,
                    'command'=>$command,
                )
            ),
            'text/html'
        );

        $this->get('mailer')->send($message);
        
    }

    public function randCommandName()
    {
        return uniqid(rand());
    }


    public function sendPayment($totalPrice)
    {
        \Stripe\Stripe::setApiKey("sk_test_UyY88AsxgBLXgp0sWFsZm8gn");

        try{
            $charge=\Stripe\Charge::create([
            'amount'=> $totalPrice*100,
            'currency'=>'eur',
            'source'=>'tok_visa'
            ]);
            return True;
        } 
        catch(\Stripe\Error\Card $e)
        {
            return False;
        }
    }


}