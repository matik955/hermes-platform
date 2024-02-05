<?php

namespace App\Admin\Account\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Admin\Account\Command\AccountBalanceUpdate;
use App\Admin\Account\Payload\AccountBalanceUpdatePayload;
use App\Admin\Account\ApiResource\AccountResource;
use App\Core\Account\Repository\AccountRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

final readonly class AccountBalanceUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private MicroMapperInterface $microMapper,
        private MessageBusInterface  $messageBus,
        private AccountRepository $accountRepository,
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof AccountBalanceUpdatePayload);

        $bookResource = $context['previous_data'] ?? null;
        assert($bookResource instanceof AccountResource);

        $command = new AccountBalanceUpdate(
            $bookResource->getId(),
            $data->balance
        );

        $this->messageBus->dispatch($command);

        $entity = $this->accountRepository->findOneBy(['id' => $uriVariables['id']]);
        return $this->microMapper->map($entity, AccountResource::class, [
            MicroMapperInterface::MAX_DEPTH => 0,
        ]);
    }
}
