<?php

namespace App\Core\Account\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueAccount extends Constraint
{
    public string $message = 'Account already exists.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
