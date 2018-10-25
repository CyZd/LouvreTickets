<?php
namespace App\Mailer;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tickets;
use App\Entity\Command;
use App\Entity\Categories;

class Mailer
{
    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager,\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->session=$session;
        $this->entityManager=$entityManager;
        $this->twig=$twig;
        $this->mailer=$mailer;
    }

    public function sendMailTickets($orderId)//command a la place
    {
        // $orderId=$this->session->get('orderToken');
        if($orderId==null)
        {
            throw $this->createNotFoundException('Envoi mail impossible-Pas de commande trouvée');
        }
        $command=$this->entityManager->getRepository(Command::class)->find($orderId);

        $mailDestination=$command->getVisitorEmail();
        $ticket=$command->getTicketsOrdered();

        $message=(new \Swift_Message('Musée du louvre: votre commande est arrivée'))
        ->setFrom('louvre-musee-reservation@louvre.gouv.fr')
        ->setTo($mailDestination)
        ->setBody(
            $this->twig->render(
                'emails/registration.html.twig',
                array(
                    'ticket'=>$ticket,
                    'command'=>$command,
                )
            ),
            'text/html'
        );
        $this->mailer->send($message);
        
    }
}
?>