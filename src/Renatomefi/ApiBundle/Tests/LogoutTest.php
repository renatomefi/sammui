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
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LogoutTest
 * @package Renatomefi\ApiBundle\Tests
 */
class LogoutTest extends WebTestCase implements AssertClientCredentialsInterface, OAuthClientInterface, AssertRestUtilsInterface, AssertOAuthInterface
{
    use AssertClientCredentials, OAuthClient, AssertRestUtils, AssertOAuth;

    /**
     * @param array $params
     * @param array $headers
     * @return null|Response
     */
    protected function getLogout($params = [], $headers = [])
    {
        $client = static::createClient();

        $client->request('GET', '/logout', $params, [], $headers);

        return $client->getResponse();
    }

    /**
     * @param $accessToken
     * @return null|Response
     */
    protected function getUserInfo($accessToken)
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info',
            ['access_token' => $accessToken]
        );

        return $client->getResponse();
    }


    /**
     * Test logout action
     */
    public function testLogout()
    {
        $response = $this->getLogout();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testLogout
     */
    public function testLogoutClearCredentialsFromCookies()
    {
        $clientCredentials = $this->getAdminCredentials();

        $client = static::createClient();

        $client->getCookieJar()->set(
            new Cookie('access_token', $clientCredentials->access_token)
        );

        $client->getCookieJar()->set(
            new Cookie('refresh_token', $clientCredentials->refresh_token)
        );

        $client->request('GET', '/logout');

        $logoutResponse = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $logoutResponse->getStatusCode());

        $response = $this->getUserInfo($clientCredentials->access_token);

        $this->assertOAuthInvalidToken($response);
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

        $adminRespone = $this->getUserInfo($clientCredentials->access_token);

//        $this->assertClientCredentialsObjStructure($adminRespone);

        $logoutResponse = $this->getLogout([
            'access_token' => $clientCredentials->access_token
        ]);

        $this->assertEquals(Response::HTTP_OK, $logoutResponse->getStatusCode());

        $response = $this->getUserInfo($clientCredentials->access_token);

        $this->assertOAuthInvalidToken($response);
    }
}