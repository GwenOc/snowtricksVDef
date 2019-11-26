<?php

namespace App\tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testPageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}