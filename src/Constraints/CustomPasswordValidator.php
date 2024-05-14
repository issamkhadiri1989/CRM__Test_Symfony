<?php

declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomPasswordValidator extends ConstraintValidator
{
    public const CUSTOM_PASSWORD_PATTERN = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (0 === \preg_match(self::CUSTOM_PASSWORD_PATTERN, $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
