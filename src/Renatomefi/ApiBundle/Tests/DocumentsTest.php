<?php

namespace Renatomefi\ApiBundle\Tests;

use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\TestBundle\MongoDB\AssertMongoId;
use Renatomefi\TestBundle\MongoDB\AssertMongoIdInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DocumentsTest
 * @package Renatomefi\TranslateBundle\Tests
 */
class DocumentsTest extends WebTestCase implements AssertMongoIdInterface, OAuthClientInterface, AssertRestUtilsInterface
{

    use AssertMongoId, OAuthClient, AssertRestUtils;

    /**
     * Test Client Document
     */
    public function testClient()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $clientManager = $kernel->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->findClientBy(['name' => LoadOAuthClient::CLIENT_NAME]);

        $this->assertNotNull($client->getId());
        $this->assertEquals(LoadOAuthClient::CLIENT_NAME, $client->getName());
        $client->setName(LoadOAuthClient::CLIENT_NAME . '-updated');
        $this->assertEquals(LoadOAuthClient::CLIENT_NAME . '-updated', $client->getName());
    }

    /**
     * Test Token Document
     */
    public function testToken()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $tokenManager = $kernel->getContainer()->get('fos_oauth_server.access_token_manager');

        $credentials = $this->getAnonymousCredentials();

        $token = $tokenManager->findTokenByToken($credentials->access_token);

        $this->assertMongoId($token->getId());
    }

}