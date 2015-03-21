<?php

namespace Flyers\FrontendBundle\Tests\Controller;

use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;
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
        $this->assertStringStartsNotWith('no-client-found-for', $clientId);
        $this->assertStringStartsNotWith('no-client-found-for', $clientSecret);
    }

    /**
     * Test invalid Client at meta tags (OAuthClientExtension for Twig)
     */
    public function testSammuiClientInvalid()
    {
        $originalName = LoadOAuthClient::$appClientName;
        LoadOAuthClient::$appClientName = LoadOAuthClient::$appClientName . '-wrong';

        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        LoadOAuthClient::$appClientName = $originalName;

        $clientId = $crawler->filterXPath('//html/head/meta[@name="sammui-oauth2-client-id"]')->attr('content');
        $clientSecret = $crawler->filterXPath('//html/head/meta[@name="sammui-oauth2-client-secret"]')->attr('content');

        $this->assertNotNull($clientId);
        $this->assertNotNull($clientSecret);
        $this->assertSame('no-client-found-for-' . LoadOAuthClient::$appClientName . '-wrong', $clientId);
        $this->assertSame('no-client-found-for-' . LoadOAuthClient::$appClientName . '-wrong', $clientSecret);
    }

}
