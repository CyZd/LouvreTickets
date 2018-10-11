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
use App\BankCall\BankCaller; 

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

use Doctrine\ORM\EntityManagerInterface;

use Twig\Environment;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Event\SessionEvent;
use App\Event\OrderEvent;
use App\Mailer\Mailer;


class PagesController extends Controller
{
    
    public function index(Environment $twig, SessionInterface $session)
    {
        $sessionEvent=new SessionEvent($session);
        $currentSession=$this->get('event_dispatcher')->dispatch(SessionEvent::SESSION,$sessionEvent)->getSession();
        return new Response($twig->render('welcomePage.html.twig'));
    }

    public function formBuildTest(Request $request, Environment $twig, SessionInterface $session, EntityManagerInterface $entityManager)
    {
        
        $ticket=new Tickets;
        $order=new Command;
        
        $order->addTicketsOrdered($ticket);

        $form=$this->createForm(CommandType::class,$order);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $purchaseStart=$form->getData();
                //create and dispatch order
                $orderEvent=new OrderEvent($order);
                $currentOrder=$this->get('event_dispatcher')->dispatch(OrderEvent::REGISTER,$orderEvent)->getOrder();

                $entityManager->persist($order);
                $entityManager->flush();

                $orderId=$order->getId();
                $session->set('orderToken',$orderId);

                $list=$currentOrder->getTicketsOrdered();

                return $this->render('orderRecap.html.twig', array('ticket'=>$list));
            }
        }
        return $this->render('formTest.html.twig', array('form'=>$form->createView()));

    }

    public function executePayment(Request $request, Environment $twig, SessionInterface $session, EntityManagerInterface $entityManager, BankCaller $caller, Mailer $mailer)
    {
        $orderId=$session->get('orderToken');
        if($orderId==null)
        {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }

        $currentOrder=$entityManager->getRepository(Command::class)->find($orderId);

        if ($currentOrder==null)
        {
            throw new \RuntimeException('Pas de commande trouvée');
        }
        else{
            $orderEvent=new OrderEvent($currentOrder);
            $currentOrder=$this->get('event_dispatcher')->dispatch(OrderEvent::GOTOPAYMENT,$orderEvent)->getOrder();
        }

        $caller->sendPayment($currentOrder->getTotalPrice());
        
        if($caller->paymentSuccess()==1)
        {
            $mailer->sendMailTickets($currentOrder);
            return $this->render('paymentMade.html.twig', array('order'=>$currentOrder, 'ticket'=>$currentOrder->getTicketsOrdered()));
        }
        else
        {
            // $entityManager=$this->getDoctrine()->getManager();
            // $orderRepo=$entityManager->getRepository(Command::class);
            // $ticketSRepo=$entityManager->getRepository(Tickets::class);
            // $orderId=$session->get('orderToken');

            // $currentOrder=$orderRepo->find($orderId);

            
            // $tickets=$entityManager->findBy(array('commandId'=>$currentOrder->getId()));

            // $entityManager->remove($currentOrder);
            // $entityManager->remove($tickets);

            return $this->render('paymentFail.html.twig');   
        }     
    }

    

    public function paymentList(EntityManagerInterface $entityManager)
    {
        $entityManager->getRepository(Tickets::class);
        $ticketList=$ticketsRepo->findAll();
        return $this->render('paymentList.html.twig', array('ticketList'=>$ticketList));
    }



}