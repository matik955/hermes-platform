<?php

namespace App\Admin\Account\Command;

use App\Admin\Account\Exception\MissingAccountException;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AccountBalanceUpdateHandler
{
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    public function __invoke(AccountBalanceUpdate $command): void
    {
        /** @var Account $account */
        $account = $this->accountRepository->findOneBy(['id' => $command->id]);

        if (null === $account) {
            throw new MissingAccountException($command->id);
        }

        $account->updateBalance($command->balance);
        $this->accountRepository->flush();
    }
}
