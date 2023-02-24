<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message = 'Este email ya está en uso. Introduce otro email.';

    /* public function validatedBy()
    {
        return static::class.'Validator';
    } */

    /* public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    } */

}
