<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use App\Event\SessionEvent;

class SessionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            SessionEvent::SESSION=>'onSessionStart',
        );
    }

    public function onSessionStart(SessionEvent $session)
    {
        $currentSession=$session->getSession();
        $currentSession->set('orderToken','');
        
    } 
}
?>