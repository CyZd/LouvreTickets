<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllTicketsSold extends Constraint
{
    public $message="Tout les billets pour ce jour ont déjà été vendus.--All tickets for this day have been sold.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
