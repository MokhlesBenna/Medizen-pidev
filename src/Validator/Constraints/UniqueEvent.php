<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEvent extends Constraint
{
    public string $message = 'Cet événement existe déjà.';
}
