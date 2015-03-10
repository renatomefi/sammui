<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use Renatomefi\ApiBundle\Document\Client;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;

/**
 * @codeCoverageIgnore
 */
trait OAuthClient
{

    /**
     * @return Client
     */
    protected function getClient()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $clientManager = $kernel->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->findClientBy(['name' => LoadOAuthClient::CLIENT_NAME]);

        if (!$client)
            throw new AuthenticationCredentialsNotFoundException('OAuth2 Client no found, unable to continue the test');

        return $client;
    }

    protected function getAnonymousCredentials()
    {

    }

    protected function getAdminCredentials()
    {

    }
}