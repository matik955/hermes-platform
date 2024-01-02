<?php

namespace App\Core\Account\Validator;

use App\Core\Account\Repository\AccountRepository;
use App\Front\Account\ApiResource\AccountResource;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueAccountValidator extends ConstraintValidator
{
    public function __construct(
        private readonly AccountRepository $accountRepository
    )
    {
    }

    /**
     * @param AccountResource $receipt
     */
    public function validate($receipt, Constraint $constraint): void
    {
        if (!$receipt instanceof AccountResource) {
            throw new UnexpectedValueException($receipt, AccountResource::class);
        }

        if (!$constraint instanceof UniqueAccount) {
            throw new UnexpectedValueException($constraint, UniqueAccount::class);
        }

        $user = $this->accountRepository->findOneBy(['login' => $receipt->getLogin()]);

        if (null !== $user) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('account.login')
                ->addViolation();
        }
    }
}
