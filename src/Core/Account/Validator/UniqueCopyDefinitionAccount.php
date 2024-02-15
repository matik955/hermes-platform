<?php

namespace App\Core\Account\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueCopyDefinitionAccount extends Constraint
{
    public string $sameTargetAndSourceAccountMessage = 'Source and target account cannot be the same.';

    public string $sourceAccountAlreadyUsedMessage = 'Account is already used as source account.';

    public string $targetAccountAlreadyUsedMessage = 'Account is already used as target account.';
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
