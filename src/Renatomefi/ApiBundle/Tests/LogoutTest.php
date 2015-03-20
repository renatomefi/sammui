<?php

namespace Renatomefi\ApiBundle\Tests;

use Renatomefi\ApiBundle\Tests\Auth\AssertClientCredentials;
use Renatomefi\ApiBundle\Tests\Auth\AssertClientCredentialsInterface;
use Renatomefi\ApiBundle\Tests\Auth\AssertOAuth;
use Renatomefi\ApiBundle\Tests\Auth\AssertOAuthInterface;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LogoutTest extends WebTestCase implements AssertClientCredentialsInterface, OAuthClientInterface, AssertRestUtilsInterface, AssertOAuthInterface
{
    use AssertClientCredentials, OAuthClient, AssertRestUtils, AssertOAuth;

    protected function getLogout($params = [], $headers = [])
    {
        $client = static::createClient();

        $client->request('GET', '/logout', $params, [], $headers);

        return $client->getResponse();
    }

    protected function getUserInfo($accessToken)
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info',
            ['access_token' => $accessToken]
        );

        return $client->getResponse();
    }


    public function testLogout()
    {
        $response = $this->getLogout();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testLogout
     */
    public function testLogoutClearCredentialsFromHeader()
    {
        $clientCredentials = $this->getAdminCredentials();

        $logoutResponse = $this->getLogout([], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $clientCredentials->access_token
        ]);

        $this->assertEquals(Response::HTTP_OK, $logoutResponse->getStatusCode());

        $response = $this->getUserInfo($clientCredentials->access_token);

        $this->assertOAuthInvalidToken($response);
    }

    /**
     * @depends testLogout
     */
    public function testLogoutClearCredentialsFromParam()
    {
        $clientCredentials = $this->getAdminCredentials();

        $logoutResponse = $this->getLogout([
            'access_token' => $clientCredentials->access_token
        ]);

        $this->assertEquals(Response::HTTP_OK, $logoutResponse->getStatusCode());

        $response = $this->getUserInfo($clientCredentials->access_token);

        $this->assertOAuthInvalidToken($response);
    }
}