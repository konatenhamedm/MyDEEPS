<?php


// src/Service/ResetPasswordService.php
namespace App\Service;

use App\Entity\ResetPasswordToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator,
        private  TokenGeneratorInterface $tokenGenerator,
    ) {}

    public function sendResetPasswordEmail($user): void
    {
        $resetRpassWOrd = new ResetPasswordToken($user);
        $resetRpassWOrd->setToken($this->tokenGenerator->generateToken());
        $this->em->persist($resetRpassWOrd);
        $this->em->flush();

        // URL du frontend Svelte pour la réinitialisation
        $frontendResetUrl = "https://mon-app-svelte.com/reset-password/{$this->tokenGenerator->generateToken()}";

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->html("<p> Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href='$frontendResetUrl'>$frontendResetUrl</a></p>");

        $this->mailer->send($email);
    }
}
