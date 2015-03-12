<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use OAuth2\OAuth2;
use Renatomefi\ApiBundle\Document\Client;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;
use Renatomefi\UserBundle\DataFixtures\MongoDB\LoadUsers;

/**
 * @codeCoverageIgnore
 */
trait OAuthClient
{

    protected $_OAuthClient;

    /**
     * @return Client
     */
    protected function getOAuthClient()
    {

        if ($this->_OAuthClient)
            return $this->_OAuthClient;

        $kernel = static::createKernel();
        $kernel->boot();

        $clientManager = $kernel->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->findClientBy(['name' => LoadOAuthClient::CLIENT_NAME]);

        if (!$client)
            throw new AuthenticationCredentialsNotFoundException('OAuth2 Client no found, unable to continue the test');

        $this->_OAuthClient = $client;
        return $client;
    }

    protected function getAnonymousCredentials()
    {
        $client = static::createClient();

        $client->request('POST', '/oauth/v2/token',
            [
                'client_id' => $this->getOAuthClient()->getPublicId(),
                'client_secret' => $this->getOAuthClient()->getSecret(),
                'grant_type' => OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $clientCredentials = $this->assertJsonResponse($response, 200, true);

        return $clientCredentials;

    }

    protected function getAdminCredentials()
    {
        $client = static::createClient();

        $client->request('POST', '/oauth/v2/token',
            [
                'client_id' => $this->getOAuthClient()->getPublicId(),
                'client_secret' => $this->getOAuthClient()->getSecret(),
                'grant_type' => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
                'username' => LoadUsers::USER_USERNAME,
                'password' => LoadUsers::USER_PASSWORD
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $clientCredentials = $this->assertJsonResponse($response, 200, true);

        return $clientCredentials;
    }
}