<?php


namespace App\Tests\listenner;

use App\Entity\AdminUser;
use App\Entity\Advert;
use App\EventListener\AdvertCreationListener;
use App\Repository\AdminUserRepository;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Mailer\MailerInterface;
class AdvertCreationListenerTest extends TestCase
{
    public function testEmailsSent(): void
    {
        $adminUser1 = new AdminUser();
        $adminUser1->setEmail('no-reply@example.com');
        $adminUser2 = new AdminUser();
        $adminUser2->setEmail('admin1@example.com');
        $adminUser3 = new AdminUser();
        $adminUser3->setEmail('admin2@example.com');

        $adminUsers = [$adminUser1, $adminUser2, $adminUser3];
        $adminUserRepositoryMock = $this->createMock(AdminUserRepository::class);
        $adminUserRepositoryMock->method('findAll')
            ->willReturn($adminUsers);

        $mailer = $this->createMock(MailerInterface::class);
        $mailer
            ->expects(static::exactly(3))
            ->method('send');

        $listener = new AdvertCreationListener(
            $mailer,
            $adminUserRepositoryMock,
        );

        $advert = new Advert();
        $advert->setId(123);
        $advert->setTitle('Test Advert');
        $advert->setContent('This is a test advert.');


        $listener->postPersist($advert);
    }
}
