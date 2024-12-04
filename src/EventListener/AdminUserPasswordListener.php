<?php
namespace App\EventListener;

use App\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: AdminUser::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: AdminUser::class)]
class AdminUserPasswordListener
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(AdminUser $adminUser): void
    {
        $this->hashPassword($adminUser);
    }

    public function preUpdate(AdminUser $adminUser): void
    {
        $this->hashPassword($adminUser);
    }

    private function hashPassword(AdminUser $adminUser): void
    {
        if ($adminUser->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($adminUser, $adminUser->getPlainPassword());
            $adminUser->setPassword($hashedPassword);
        }
    }
}
