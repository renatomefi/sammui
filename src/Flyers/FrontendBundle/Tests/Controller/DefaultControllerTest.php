<?php

namespace Flyers\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DefaultControllerTest
 * @package Flyers\FrontendBundle\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test if ng-app directive exists and is sammui
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function testAngularSammui()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals('sammui', $crawler->filterXPath('//html')->attr('ng-app'));

        return $crawler;
    }

    /**
     * @depends testAngularSammui
     * @param Crawler $crawler
     */
    public function testSammuiClient(Crawler $crawler)
    {
        $clientId = $crawler->filterXPath('//html/head/meta[@name="sammui-oauth2-client-id"]')->attr('content');
        $clientSecret = $crawler->filterXPath('//html/head/meta[@name="sammui-oauth2-client-secret"]')->attr('content');

        $this->assertNotNull($clientId);
        $this->assertNotNull($clientSecret);
        $this->assertTrue((is_string($clientId)), $clientId);
        $this->assertTrue((is_string($clientSecret)), $clientSecret);
    }
}
