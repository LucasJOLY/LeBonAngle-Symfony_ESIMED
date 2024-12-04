<?php

namespace App\Tests\api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PictureApiTest extends ApiTestCase
{
    use ResetDatabase;
    public function testCreateAPicture(): void
    {
        $originalFile = __DIR__ . '/../../fixtures/image.jpg';
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        dump($tempFile);
        copy($originalFile, $tempFile);

        $file = new UploadedFile($tempFile, 'image.jpg', null, null, true);

        dump($file);

        $client = self::createClient();
        $client->request('POST', '/api/pictures', [
            'headers' => ['Content-Type' => 'multipart/form-data', 'Accept' => 'application/ld+json'],
            'extra' => [
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }
}