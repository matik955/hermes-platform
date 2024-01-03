<?php

namespace App\Admin\User\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\User\Entity\User;
use App\Core\User\Repository\UserRepository;
use App\Front\User\ApiResource\UserResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserProcessor implements ProcessorInterface
{
    private UserRepository $userRepository;

    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof UserResource);

        $entity = new User(
            $data->getEmail(),
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entity,
            $data->getPassword()
        );

        $entity->setPassword($hashedPassword);

        $this->persistProcessor->process($entity, $operation, $uriVariables);
        $data->setId($entity->getId());

        return $data;
    }
}
