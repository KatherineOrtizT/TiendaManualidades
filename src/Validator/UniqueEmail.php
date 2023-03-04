<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class UniqueEmail extends Constraint
{
    public $message = 'Este email ya está en uso. Introduce otro email.';


    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }

}
