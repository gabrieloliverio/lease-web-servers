<?php 

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServerTest extends WebTestCase
{
    private const SEARCH_ENDPOINT = 'http://localhost:8000/api/servers/';

    public function testSearchFound()
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', self::SEARCH_ENDPOINT, [
            'location' => 'FrankfurtFRA-10',
            'ram' => '32GB',
            'storage' => '24TB',
            'hard_disk_type' => 'SATA',
        ]);

        $results = json_decode($client->getResponse()->getContent())->results;
        $this->assertCount(4, $results);
        $this->assertEquals('Supermicro SC846Intel Xeon E5620', $results[0]->model);
    }

    public function testSearchNotFound()
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', self::SEARCH_ENDPOINT, [
            'location' => 'FrankfurtFRA-10',
            'ram' => '100GB',
            'storage' => '72GB',
            'hard_disk_type' => 'SATA',
        ]);
        $this->assertResponseIsSuccessful();
        
        $results = json_decode($client->getResponse()->getContent())->results;
        $this->assertEmpty($results);
    }

    public function testSearchNoCriteria()
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', self::SEARCH_ENDPOINT);
        $this->assertResponseIsSuccessful();
        
        $results = json_decode($client->getResponse()->getContent())->results;
        $this->assertCount(486, $results);
    }
}