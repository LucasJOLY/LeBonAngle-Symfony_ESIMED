<?php

namespace App\Tests\listenner;

use App\Entity\AdminUser;
use App\EventListener\AdminUserPasswordListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserPasswordListenerTest extends TestCase
{
    public function testPrePersistHashesPassword(): void
    {
        $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(AdminUser::class),
                $this->equalTo('plainPassword123')
            )
            ->willReturn('hashedPassword123');
        $adminUser = new AdminUser();
        $adminUser->setPlainPassword('plainPassword123');
        $listener = new AdminUserPasswordListener($passwordHasherMock);
        $listener->prePersist($adminUser);


        $this->assertEquals('hashedPassword123', $adminUser->getPassword());
    }

    public function testPreUpdateHashesPassword(): void
    {
        $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(AdminUser::class),
                $this->equalTo('plainPassword123')
            )
            ->willReturn('hashedPassword123');
        $adminUser = new AdminUser();
        $adminUser->setPlainPassword('plainPassword123');
        $listener = new AdminUserPasswordListener($passwordHasherMock);
        $listener->preUpdate($adminUser);
        $this->assertEquals('hashedPassword123', $adminUser->getPassword());
    }
}
