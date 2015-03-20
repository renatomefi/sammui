<?php

namespace Flyers\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Flyers\FrontendBundle\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test if ng-app directive exists and is sammui
     */
    public function testAngularSammui()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals('sammui', $crawler->filterXPath('//html')->attr('ng-app'));
    }
}
