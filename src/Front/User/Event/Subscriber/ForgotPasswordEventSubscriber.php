<?php

namespace App\Front\User\Event\Subscriber;

use App\Core\User\Repository\UserRepository;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Twig\Environment;

final readonly class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MailerInterface             $mailer,
        private Environment                 $twig,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository              $userRepository
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $message = (new Email())
            ->from($_ENV['MAILER_SENDER'])
            ->to($user->getEmail())
            ->subject('Reset your Hermes Account password')
            ->html($this->twig->render(
                'Front/email/ResetPassword.html.twig',
                [
                    'token' => $passwordToken->getToken(),
                ]
            ));
        $this->mailer->send($message);
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $event->getPassword()
        );

        $user->setPassword($hashedPassword);
        $this->userRepository->add($user);
    }
}
