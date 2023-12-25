<?php

namespace App\Validator\Constraints;

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TaxNumberConstraint) {
            throw new \InvalidArgumentException('Invalid constraint type');
        }

        if (!preg_match('/^[A-Z]{2}\d+$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}