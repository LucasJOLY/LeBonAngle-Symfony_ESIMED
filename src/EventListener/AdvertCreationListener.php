<?php

namespace App\EventListener;

use App\Entity\Advert;
use App\Repository\AdminUserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\BodyRendererInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Advert::class)]
class AdvertCreationListener
{
    private MailerInterface $mailer;
    private AdminUserRepository $adminUserRepository;

    public function __construct(MailerInterface $mailer, AdminUserRepository $adminUserRepository)
    {
        $this->mailer = $mailer;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function postPersist(Advert $entity): void
    {
        $adminUsers = $this->adminUserRepository->findAll();

        foreach ($adminUsers as $adminUser) {
            $this->sendNotification($adminUser->getEmail(), $entity);
        }
    }

    private function sendNotification(string $email, Advert $advert): void
    {
        $adminUrl = 'http://localhost:8000/admin/advert/' . $advert->getId();
        $publishUrl = 'http://localhost:8000/admin/advert/' . $advert->getId() . '/publish';
        $rejectUrl = 'http://localhost:8000/admin/advert/' . $advert->getId() . '/reject';

        $emailMessage = (new TemplatedEmail())
            ->from('no-reply@example.com')
            ->to($email)
            ->subject('Nouvelle annonce créée')
            ->htmlTemplate('emails/advert_created.html.twig')
            ->context([
                'advert' => $advert,
                'adminUrl' => $adminUrl,
                'publishUrl' => $publishUrl,
                'rejectUrl' => $rejectUrl,
            ]);
        $this->mailer->send($emailMessage);
    }
}
