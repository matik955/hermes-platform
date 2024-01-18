<?php

namespace App\Front\Account\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\Account\Entity\CopyDefinition;
use App\Core\Account\Repository\AccountRepository;
use App\Core\Account\Repository\CopyDefinitionRepository;
use App\Front\Account\ApiResource\CopyDefinitionResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CopyDefinitionProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)]
        private readonly ProcessorInterface $removeProcessor,
        private readonly AccountRepository $accountRepository,
        private readonly CopyDefinitionRepository $copyDefinitionRepository
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof  CopyDefinitionResource);

        if ($data->getId()) {
            $entity = $this->copyDefinitionRepository->find($data->getId());
        } else {
            $sourceAccount = $this->accountRepository->findOneBy(['id' => $data->getSourceAccount()->getId()]);
            $targetAccount = $this->accountRepository->findOneBy(['id' => $data->getTargetAccount()->getId()]);

            $entity = new CopyDefinition(
                $sourceAccount,
                $targetAccount,
            );
        }

        if ($operation instanceof DeleteOperationInterface) {
            $this->removeProcessor->process($entity, $operation, $uriVariables, $context);

            return null;
        }

        $this->persistProcessor->process($entity, $operation, $uriVariables);
        $data->setId($entity->getId());

        return $data;
    }
}
