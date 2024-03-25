<?php

namespace App\Admin\Account\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Admin\Account\ApiResource\AccountLogResource;
use App\Core\Account\Entity\AccountLog;
use App\Core\Account\Repository\AccountLogRepository;
use App\Core\Account\Repository\AccountRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class AccountLogProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface   $persistProcessor,
        private AccountLogRepository $accountLogRepository,
        private AccountRepository $accountRepository
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof  AccountLogResource);

        if ($data->getId()) {
            $entity = $this->accountLogRepository->find($data->getId());
        } else {
            $owner = $this->accountRepository->findOneBy(['id' => $uriVariables['accountId']]);

            $entity = new AccountLog(
                $data->getType(),
                $data->getData(),
                $owner,
                $data->getCopyDefinition(),
            );
        }

        $this->persistProcessor->process($entity, $operation, $uriVariables, $context);
        $data->setId($entity->getId());

        return $data;
    }
}
