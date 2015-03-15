<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\AssertOAuth;
use Renatomefi\ApiBundle\Tests\Auth\AssertOAuthInterface;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LanguageControllerTest extends WebTestCase implements OAuthClientInterface, AssertOAuthInterface
{
    use OAuthClient, AssertOAuth;

    public function testAdminActionAuthRequired()
    {
        $client = static::createClient();
        $client->request('GET', '/l10n/admin');

        $this->assertOAuthRequired($client->getResponse());
    }
}