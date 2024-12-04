<?php

namespace App\Tests\api;

use App\Factory\AdvertFactory;
use App\Factory\CategoryFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AdvertApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetAdverts(): void
    {
        $category = CategoryFactory::createOne(['name' => 'Category 1']);
        AdvertFactory::createMany(5, ['category' => $category]);

        $response = static::createClient()->request('GET', '/api/adverts', [
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray()['member'];
        $this->assertCount(5, $data);
    }


    public function testGetAdvertsOrderByPrice(): void
    {
        $category = CategoryFactory::createOne(['name' => 'Category 1']);
        AdvertFactory::createMany(5, ['category' => $category]);

        $response = static::createClient()->request('GET', '/api/adverts', [
            'query' => ['order[price]' => 'desc'],
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray()['member'];
        $this->assertCount(5, $data);
        $this->assertLessThanOrEqual($data[0]['price'], $data[1]['price']);
        $this->assertLessThanOrEqual($data[1]['price'], $data[2]['price']);
        $this->assertLessThanOrEqual($data[2]['price'], $data[3]['price']);
        $this->assertLessThanOrEqual($data[3]['price'], $data[4]['price']);
    }

    public function testFilterByPriceMinMax (): void
    {
        $category = CategoryFactory::createOne(['name' => 'Category 1']);
        AdvertFactory::createMany(5, ['category' => $category, 'price' => 15]);
        AdvertFactory::createOne(['category' => $category, 'price' => 11]);

        $response = static::createClient()->request('GET', '/api/adverts', [
            'query' => ['price[gte]' => 10, 'price[lte]' => 14],
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray()['member'];
        $this->assertCount(1, $data);
    }

    public function testFilterAdvertsByCategory(): void
    {
        $category = CategoryFactory::createOne(['name' => 'Category 1']);
        $advert = AdvertFactory::createOne(['category' => $category]);

        $response = static::createClient()->request('GET', '/api/adverts', [
            'query' => ['category' => '/api/categories/'.$category->getId()],
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->assertCount(1, $data['member']);
        $this->assertEquals($advert->getId(), $data['member'][0]['id']);
    }


    public function testPostAdvert(): void
    {
        $category = CategoryFactory::createOne(['name' => 'Category 1']);
        $originalFile = __DIR__ . '/../../fixtures/image.jpg';
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        copy($originalFile, $tempFile);

        $file = new UploadedFile($tempFile, 'image.jpg', null, null, true);

        $client = self::createClient();
        $responseImage = $client->request('POST', '/api/pictures', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $pictureId = $responseImage->toArray()['@id'];


        $response = static::createClient()->request('POST', '/api/adverts', [
            'json' => [
                'title' => 'New Advert',
                'content' => 'This is a new advert',
                'price' => 100.0,
                'email' => 'test@email.fr',
                'author' => 'Test Author',
                'category' => '/api/categories/'.$category->getId(),
                'photos' => [$pictureId],
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $advertData = $response->toArray();
        $this->assertCount(1, $advertData['photos']);
    }

}
