<?php

namespace App\Admin\Account\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use App\Core\User\Entity\User;
use App\Core\User\Repository\UserRepository;
use App\Admin\Account\ApiResource\AccountResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AccountProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly UserRepository $userRepository,
        private readonly AccountRepository $accountRepository
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof  AccountResource);

        if ($data->getId()) {
            $entity = $this->accountRepository->find($data->getId());
        } else {
            /** @var User $user */
            $user = $this->userRepository->findOneBy(['id' => $uriVariables['userId']]);

            $entity = new Account(
                $data->getLogin(),
                $data->getPassword(),
                $data->getTradeServer(),
                $data->getMtVersion(),
                $user,
                $data->getBalance(),
            );
        }

        $this->persistProcessor->process($entity, $operation, $uriVariables);
        $data->setId($entity->getId());

        return $data;
    }
}
