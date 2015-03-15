<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\AssertOAuth;
use Renatomefi\ApiBundle\Tests\Auth\AssertOAuthInterface;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LanguageControllerTest
 * @package Renatomefi\TranslateBundle\Tests\Controller
 */
class LanguageControllerTest extends WebTestCase implements OAuthClientInterface, AssertOAuthInterface, AssertRestUtilsInterface
{
    use OAuthClient, AssertOAuth, AssertRestUtils;

    /**
     * Test the need of a OAuth Authentication
     */
    public function testAdminActionAuthRequired()
    {
        $client = static::createClient();
        $client->request('GET', '/l10n/admin');

        $this->assertOAuthRequired($client->getResponse());
    }

    /**
     * Test OAuth with wrong role, access denied expected
     */
    public function testAdminActionRoleWrong()
    {
        $credentials = $this->getAnonymousCredentials();

        $client = static::createClient();
        $crawler = $client->request('GET', '/l10n/admin', ['access_token' => $credentials->access_token]);

        $response = $client->getResponse();

        $this->assertSame(403, $response->getStatusCode());
        $this->assertTrue($response->isForbidden());
        $this->assertGreaterThan(0, $crawler->filter('head')->filter('title:contains("Access Denied")')->count());
    }

    /**
     * Funcional test for admin action
     */
    public function testAdminAction()
    {
        $credentials = $this->getAdminCredentials();

        $client = static::createClient();
        $crawler = $client->request('GET', '/l10n/admin', ['access_token' => $credentials->access_token]);

        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertContains('translate-admin-index', $crawler->filterXPath("//div[@ui-content-for='title']/span")->html());
    }

}