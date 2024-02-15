<?php

namespace App\Core\Account\Validator;

use App\Core\Account\Interface\CopyDefinitionInterface;
use App\Core\Account\Repository\CopyDefinitionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueCopyDefinitionAccountValidator extends ConstraintValidator
{
    public function __construct(
        private readonly CopyDefinitionRepository $copyDefinitionRepository
    )
    {
    }

    /**
     * @param CopyDefinitionInterface $receipt
     */
    public function validate($receipt, Constraint $constraint): void
    {
        if (!$receipt instanceof CopyDefinitionInterface) {
            throw new UnexpectedValueException($receipt, CopyDefinitionInterface::class);
        }

        if (!$constraint instanceof UniqueCopyDefinitionAccount) {
            throw new UnexpectedValueException($constraint, UniqueCopyDefinitionAccount::class);
        }

        $targetAccount = $receipt->getTargetAccount();
        $sourceAccount = $receipt->getSourceAccount();

        if ($targetAccount->getId() === $sourceAccount->getId()) {
            $this->context
                ->buildViolation($constraint->sameTargetAndSourceAccountMessage)
                ->addViolation();
        }

        $sourceAccountCopyDefinition = $this->copyDefinitionRepository->findOneBy(['sourceAccount' => $targetAccount->getId()]);

        if (null !== $sourceAccountCopyDefinition) {
            $this->context
                ->buildViolation($constraint->sourceAccountAlreadyUsedMessage)
                ->addViolation();
        }

        $targetAccountCopyDefinition = $this->copyDefinitionRepository->findOneBy(['targetAccount' => $sourceAccount->getId()]);

        if (null !== $targetAccountCopyDefinition) {
            $this->context
                ->buildViolation($constraint->targetAccountAlreadyUsedMessage)
                ->addViolation();
        }
    }
}
