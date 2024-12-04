<?php
namespace App\Tests\api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CategoryFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
class CategoryApiTest extends ApiTestCase
{

    use Factories;
    use ResetDatabase;
    public function testGetCategories(): void
    {
        CategoryFactory::createMany(5);
        $response = static::createClient()->request('GET', '/api/categories');

        $this->assertResponseIsSuccessful();
        $this->assertCount(5, $response->toArray());
    }

    public function testGetUniqueCategory(): void
    {
        $category = CategoryFactory::createOne(['name' => 'Test Category']);

        // Teste la récupération de cette catégorie
        $response = static::createClient()->request('GET', '/api/categories/'.$category->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'id' => $category->getId(),
            'name' => 'Test Category',
        ]);
    }
}
