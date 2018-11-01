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

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PagesController extends Controller
{
    /**
     * @Route("/{_locale}/", name="index")
     */
    public function index(Environment $twig, SessionInterface $session)
    {
        $sessionEvent=new SessionEvent($session);
        $currentSession=$this->get('event_dispatcher')->dispatch(SessionEvent::SESSION, $sessionEvent)->getSession();
        return new Response($twig->render('welcomePage.html.twig'));
    }

    /**
     * @Route("/{_locale}/formtest/", name="formTest")
     */
    public function formBuildTest(Request $request, Environment $twig, SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $ticket=new Tickets;
        $order=new Command;
        
        $order->addTicketsOrdered($ticket);

        $form=$this->createForm(CommandType::class, $order);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $purchaseStart=$form->getData();
                //create and dispatch order
                $orderEvent=new OrderEvent($order);
                $currentOrder=$this->get('event_dispatcher')->dispatch(OrderEvent::REGISTER, $orderEvent)->getOrder();

                $entityManager->persist($order);
                $entityManager->flush();

                $orderId=$order->getId();
                $session->set('orderToken', $orderId);

                $list=$currentOrder->getTicketsOrdered();

                return $this->render('orderRecap.html.twig', array('ticket'=>$list));
            }
        }
        return $this->render('formTest.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/{_locale}/executePayment/", name="exec_payment")
     */
    public function executePayment(Request $request, Environment $twig, SessionInterface $session, EntityManagerInterface $entityManager, BankCaller $caller, Mailer $mailer)
    {
        $orderId=$session->get('orderToken');
        if ($orderId==null) {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }

        $currentOrder=$entityManager->getRepository(Command::class)->find($orderId);

        if ($currentOrder==null) {
            throw new \RuntimeException('Pas de commande trouvée');
        } else {
            $orderEvent=new OrderEvent($currentOrder);
            $currentOrder=$this->get('event_dispatcher')->dispatch(OrderEvent::GOTOPAYMENT, $orderEvent)->getOrder();
        }

        $caller->sendPayment($currentOrder->getTotalPrice());
        
        if ($caller->paymentSuccess()==1) {
            $this->sendMail($entityManager, $mailer, $session);
            return $this->render('paymentMade.html.twig', array('order'=>$currentOrder, 'ticket'=>$currentOrder->getTicketsOrdered()));
        } else {
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

    /**
     * @Route("/{_locale}/mail_order/", name="mail_order")
     */
    public function sendMail(EntityManagerInterface $entityManager, Mailer $mailer, SessionInterface $session)
    {
        $orderId=$session->get('orderToken');
        $currentOrder=$entityManager->getRepository(Command::class)->find($orderId);
        if ($currentOrder==null) {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }
        $mailer->sendMailTickets($currentOrder);
    }

    
    /**
     * @Route("/{_locale}/payment_list/", name="payment_list")
     */
    public function paymentList(EntityManagerInterface $entityManager)
    {
        $ticketList=$entityManager->getRepository(Tickets::class)->findAll();
        return $this->render('paymentList.html.twig', array('ticketList'=>$ticketList));
    }
}
