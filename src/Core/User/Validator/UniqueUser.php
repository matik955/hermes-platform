<?php

namespace App\Core\User\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUser extends Constraint
{
    public string $message = 'User already exists.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
