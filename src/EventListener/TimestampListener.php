<?php

namespace App\EventListener;

use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::prePersist, method: 'prePersistAdvert', entity: Advert::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersistPicture', entity: Picture::class)]
class TimestampListener
{

    public function prePersistAdvert(Advert $advert): void
    {
            if (null === $advert->getCreatedAt()) {
                $advert->setCreatedAt(new \DateTimeImmutable());
            }

    }

    public function prePersistPicture(Picture $picture): void
    {
            if (null === $picture->getCreatedAt()) {
                $picture->setCreatedAt(new \DateTimeImmutable());
            }

    }
}
