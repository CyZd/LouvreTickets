<?php    
namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllTicketsSold extends Constraint
{
    public $message="Tout les billets pour ce jour ont déjà été vendus.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}



?>