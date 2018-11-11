<?php
namespace App\Mailer;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;
use App\Entity\Command;
use App\Entity\Category;

class Mailer
{
    private $twig;
    private $mailer;


    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig=$twig;
        $this->mailer=$mailer;
    }

    public function sendMailTickets(Command $order)
    {
        $mailDestination=$order->getVisitorEmail();
        $ticket=$order->getTicketsOrdered();

        $message=(new \Swift_Message('Musée du louvre: votre commande est arrivée'))
        ->setFrom('louvre-musee-reservation@louvre.gouv.fr')
        ->setTo($mailDestination)
        ->setBody(
            $this->twig->render(
                'emails/registration.html.twig',
                array(
                    'ticket'=>$ticket,
                    'command'=>$order,
                )
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
}
