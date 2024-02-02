<?php

namespace App\Admin\Account\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Admin\Account\Command\AccountBalanceUpdate;
use App\Admin\Account\Payload\AccountBalanceUpdatePayload;
use App\Admin\Account\ApiResource\AccountResource;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class AccountBalanceUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private MessageBusInterface $messageBus
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

        return $data;
    }
}
