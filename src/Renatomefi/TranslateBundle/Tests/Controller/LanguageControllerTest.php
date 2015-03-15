<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\AssertOAuth;
use Renatomefi\ApiBundle\Tests\Auth\AssertOAuthInterface;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LanguageControllerTest
 * @package Renatomefi\TranslateBundle\Tests\Controller
 */
class LanguageControllerTest extends WebTestCase implements OAuthClientInterface, AssertOAuthInterface
{
    use OAuthClient, AssertOAuth;

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
        $this->markTestIncomplete('Todo');
    }

    /**
     * Funcional test for admin action
     */
    public function testAdminAction()
    {
        $this->markTestIncomplete('Todo functional test');
    }

}