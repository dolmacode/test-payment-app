<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TaxNumberConstraint extends Constraint
{
    public $message = 'Invalid tax number: {{ string }}';
}