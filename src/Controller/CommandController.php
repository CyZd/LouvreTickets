<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ticket;
use App\Entity\Command;
use App\Entity\Category;
use App\Repository\CommandRepository;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TicketType;
use App\Form\CommandType;
use App\BankCall\BankCaller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

// use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
// use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

use Doctrine\ORM\EntityManagerInterface;

use Twig\Environment;

// use Symfony\Component\Form\FormEvent;
// use Symfony\Component\Form\FormEvents;
// use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Event\SessionEvent;
use App\Event\OrderEvent;
use App\Mailer\Mailer;

use Symfony\Component\Routing\Annotation\Route;

// use Symfony\Component\Translation\Translator;
// use Symfony\Component\Translation\Loader\PhpFileLoader;
// use Symfony\Bridge\Twig\Extension\TranslationExtension;

// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommandController extends AbstractController
{
    /**
     * @Route("/{_locale}/", name="index")
     */
    public function index(Environment $twig, SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $sessionEvent=new SessionEvent($session);
        $currentSession=$dispatcher->dispatch(SessionEvent::SESSION, $sessionEvent)->getSession();
        return new Response($twig->render('welcomePage.html.twig'));
    }

    /**
     * @Route("/{_locale}/formtest/", name="formTest")
     */
    public function formBuildTest(Request $request, Environment $twig, SessionInterface $session, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $ticket=new Ticket;
        $order=new Command;
        
        $order->addTicketsOrdered($ticket);

        $form=$this->createForm(CommandType::class, $order);

        if ($request->isMethod('POST')) {
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $purchaseStart=$form->getData();
                //create and dispatch order
                $orderEvent=new OrderEvent($order);
                $currentOrder=$dispatcher->dispatch(OrderEvent::REGISTER, $orderEvent)->getOrder();

                $entityManager->persist($order);
                $entityManager->flush();

                $session->set('orderToken', $order->getId());

                $list=$currentOrder->getTicketsOrdered();

                return $this->render('orderRecap.html.twig', array('order'=>$currentOrder,'ticket'=>$list));
            }
        }
        return $this->render('formTest.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/{_locale}/executePayment/", name="exec_payment")
     */
    public function executePayment(SessionInterface $session, EntityManagerInterface $entityManager, CommandRepository $commandRepository, BankCaller $caller, Mailer $mailer, EventDispatcherInterface $dispatcher)
    {
        $orderId=$session->get('orderToken');
        if ($orderId==null) {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }

        $currentOrder=$entityManager->getRepository(Command::class)->find($orderId);

        if ($currentOrder==null) {
            throw new \RuntimeException('Pas de commande trouvée');
        } 

        $orderEvent=new OrderEvent($currentOrder);
        $currentOrder=$dispatcher->dispatch(OrderEvent::GOTOPAYMENT, $orderEvent)->getOrder();


        $caller->sendPayment($currentOrder->getTotalPrice());
        
        if ($caller->paymentSuccess()) {
            $this->sendMail($entityManager, $commandRepository, $mailer, $session);
            $currentOrder->setHasBeenPaid(true);
            return $this->render('paymentMade.html.twig', array('order'=>$currentOrder, 'ticket'=>$currentOrder->getTicketsOrdered()));
        } else {
            $currentOrder->setHasBeenPaid(false);
            return $this->render('paymentFail.html.twig');
        }
    }

    /**
     * @Route("/{_locale}/mail_order/", name="mail_order")
     */
    public function sendMail(EntityManagerInterface $entityManager,CommandRepository $commandRepository, Mailer $mailer, SessionInterface $session)
    {
        $orderId=$session->get('orderToken');
        $currentOrder=$commandRepository->find($orderId);
        if ($currentOrder==null) {
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');
        }
        $mailer->sendMailTickets($currentOrder);
    }

    
    /**
     * @Route("/{_locale}/payment_list/", name="payment_list")
     */
    public function paymentList(TicketRepository $ticketRepository)
    {
        $ticketList=$ticketRepository->findAll();
        return $this->render('paymentList.html.twig', array('ticketList'=>$ticketList));
    }
    
}
