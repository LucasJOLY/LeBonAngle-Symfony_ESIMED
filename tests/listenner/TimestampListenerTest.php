<?php

namespace App\Tests\listenner;

use App\Entity\Advert;
use App\Entity\Picture;
use App\EventListener\TimestampListener;
use PHPUnit\Framework\TestCase;

class TimestampListenerTest extends TestCase
{
    public function testPrePersistAdvertSetsCreatedAt(): void
    {
        $advert = new Advert();
        $listener = new TimestampListener();
        $listener->prePersistAdvert($advert);
        $this->assertNotNull($advert->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $advert->getCreatedAt());
    }

    public function testPrePersistPictureSetsCreatedAt(): void
    {
        $picture = new Picture();
        $listener = new TimestampListener();
        $listener->prePersistPicture($picture);
        $this->assertNotNull($picture->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $picture->getCreatedAt());
    }

}
