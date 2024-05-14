<?php

declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CustomPassword extends Constraint
{
    public string $message = 'the password you provided does not fit our policies.';

    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null, string $message = '')
    {
        parent::__construct($options, $groups, $payload);
        $this->message = '' === $message ? $this->message : $message;
    }

    public function getTargets(): string|array
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }
}
