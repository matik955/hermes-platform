<?php

namespace App\Front\Account\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\Account\Entity\CopyDefinition;
use App\Core\Account\Repository\AccountRepository;
use App\Core\Account\Repository\CopyDefinitionRepository;
use App\Front\Account\ApiResource\CopyDefinitionResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CopyDefinitionProcessor implements ProcessorInterface
{
    private CopyDefinitionRepository $copyDefinitionRepository;

    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly AccountRepository $accountRepository
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

        $this->persistProcessor->process($entity, $operation, $uriVariables);
        $data->setId($entity->getId());

        return $data;
    }
}
