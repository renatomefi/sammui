<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use OAuth2\OAuth2;
use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;
use Renatomefi\ApiBundle\Document\Client;
use Renatomefi\UserBundle\DataFixtures\MongoDB\LoadUsers;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class OAuthClient
 * @package Renatomefi\ApiBundle\Tests\Auth
 * @codeCoverageIgnore
 */
trait OAuthClient
{

    /**
     * @var
     */
    protected $_OAuthClient;

    /**
     * @return Client
     */
    public function getOAuthClient()
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

    /**
     * @param array $params
     * @return mixed
     */
    public function queryOAuth2Token($params = [])
    {
        if (!method_exists($this, 'assertJsonResponse'))
            throw new \PHPUnit_Framework_Exception('You must to use AssertRestUtils trait in order to use this OAuthClient trait');

        $client = static::createClient();

        $defaultParams = [
            'client_id' => $this->getOAuthClient()->getPublicId(),
            'client_secret' => $this->getOAuthClient()->getSecret(),
        ];

        $client->request('POST', '/oauth/v2/token', array_merge($defaultParams, $params), [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        return $this->assertJsonResponse($client->getResponse(), 200, true);
    }

    /**
     * @return mixed
     */
    public function getAnonymousCredentials()
    {
        return $this->queryOAuth2Token([
            'grant_type' => OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS
        ]);
    }

    /**
     * @return mixed
     */
    public function getAdminCredentials()
    {
        return $this->queryOAuth2Token([
            'grant_type' => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
            'username' => LoadUsers::USER_USERNAME,
            'password' => LoadUsers::USER_PASSWORD
        ]);
    }

    public function getCredentialsByRole($role)
    {
        switch ($role) {
            case 'ROLE_ADMIN':
                return $this->getAdminCredentials();
                break;
            case 'ROLE_USER':
                throw new \Exception('ROLE_USER not implemented yet');
                break;
            default:
                return $this->getAnonymousCredentials();
                break;
        }
    }
}