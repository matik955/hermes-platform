<?php

namespace App\Core\User\Validator;

use App\Core\User\Interface\UserResourceInterface;
use App\Core\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUserValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    /**
     * @param UserResourceInterface $receipt
     */
    public function validate($receipt, Constraint $constraint): void
    {
        if (!$receipt instanceof UserResourceInterface) {
            throw new UnexpectedValueException($receipt, UserResourceInterface::class);
        }

        if (!$constraint instanceof UniqueUser) {
            throw new UnexpectedValueException($constraint, UniqueUser::class);
        }

        $user = $this->userRepository->findOneBy(['email' => $receipt->getEmail()]);

        if (null !== $user) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('email')
                ->addViolation();
        }
    }
}
