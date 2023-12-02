<?php

namespace App\Front\Account\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use App\Front\Account\ApiResource\AccountResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AccountProcessor implements ProcessorInterface
{
    private AccountRepository $accountRepository;

    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof  AccountResource);

        if ($data->getId()) {
            $entity = $this->accountRepository->find($data->getId());
        } else {
            $entity = new Account(
                $data->getLogin(),
                $data->getPassword(),
                $data->getTradeServer(),
                $data->getMtVersion(),
                $data->getBalance(),
                $data->getUser()
            );
        }

        $this->persistProcessor->process($entity, $operation, $uriVariables);
        $data->setId($entity->getId());

        return $data;
    }
}
