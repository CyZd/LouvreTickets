<?
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionEvent extends Event
{
    protected $session;

    const SESSION='session.started';

    public function __construct(SessionInterface $session)
    {
        $this->session=$session;
    }

    public function getSession()
    {
        return $this->session;
    }


}
?>