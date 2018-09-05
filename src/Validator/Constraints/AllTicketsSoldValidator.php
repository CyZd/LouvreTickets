<?php    
namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use App\Entity\Tickets;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AllTicketsSoldValidator extends ConstraintValidator
{   
    private $repository;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->repository=$manager->getRepository(Tickets::class);
    }

    public function validate($ticket, Constraint $constraint)
    {
        $date=$ticket->getDesiredDate();

        $alreadySold=$this->repository->findAllForOneDate($date);
        dump(count($alreadySold));

        if(count($alreadySold)>= 1000)
        {
            $this->context->buildViolation($constraint->message)
            ->atPath('DesiredDate')
            ->addViolation();
        }
    }
}



?>