<?php

namespace Renatomefi\ApiBundle\Tests;

use Renatomefi\ApiBundle\Tests\Auth\UserInfo;
use Renatomefi\ApiBundle\Tests\Auth\ClientCredentials;
use Renatomefi\TestBundle\Rest\RestUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTest extends WebTestCase
{

    use UserInfo, ClientCredentials, RestUtils;

    protected $_clientId = '54d2028ceabc88600a8b4567_qss71wwodiosk84gk4gwwk8s40k48wgg0cgkw8wwkwwgkcg44';
    protected $_clientSecret = '5o808pbhkw84kcwggocc0ogos8c44socccgc0880koggoc08sk';


    public function testAnonymousOAuth()
    {
        $client = static::createClient();

        $client->request('GET', '/oauth/v2/token',
            [
                'client_id' => $this->_clientId,
                'client_secret' => $this->_clientSecret,
                'grant_type' => 'client_credentials'
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $clientCredentials = $this->assertJsonResponse($response, 200, true);

        $this->assertClientCredentialsObjStructure($clientCredentials);
        $this->assertClientCredentialsToken($clientCredentials);
        $this->assertObjectNotHasAttribute('refresh_token', $clientCredentials);

        $this->assertEquals('bearer', $clientCredentials->token_type);

        return [[$clientCredentials]];
    }

    public function testPasswordOAuth()
    {
        $client = static::createClient();

        $client->request('GET', '/oauth/v2/token',
            [
                'client_id' => $this->_clientId,
                'client_secret' => $this->_clientSecret,
                'grant_type' => 'password',
                'username' => 'sammui',
                'password' => 'sammui'
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $clientCredentials = $this->assertJsonResponse($response, 200, true);

        $this->assertClientCredentialsObjStructure($clientCredentials);
        $this->assertClientCredentialsToken($clientCredentials, 'access_token');
        $this->assertClientCredentialsToken($clientCredentials, 'refresh_token');

        return [[$clientCredentials]];
    }

    /**
     * @depends      testPasswordOAuth
     * @dataProvider testPasswordOAuth
     */
    public function testOAuthRefreshToken($clientCredentials)
    {
        $client = static::createClient();

        $client->request('GET', '/oauth/v2/token',
            [
                'client_id' => $this->_clientId,
                'client_secret' => $this->_clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $clientCredentials->refresh_token
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $refreshClientCredentials = $this->assertJsonResponse($response, 200, true);

        $this->assertClientCredentialsObjStructure($refreshClientCredentials);
        $this->assertClientCredentialsToken($refreshClientCredentials, 'access_token');
        $this->assertClientCredentialsToken($refreshClientCredentials, 'refresh_token');

        // Old and New Tokens should not be the same
        $this->assertNotEquals($clientCredentials->access_token, $refreshClientCredentials->access_token);
        $this->assertNotEquals($clientCredentials->refresh_token, $refreshClientCredentials->refresh_token);
    }

    public function testEmptySession()
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info');

        $response = $client->getResponse();

        $userInfo = $this->assertJsonResponse($response, 200, true);

        $this->assertUserInfoObjStructure($userInfo);
        $this->assertUserInfoObjNoAuth($userInfo);
    }

    public function testLogoutRedirect()
    {
        $client = static::createClient();

        $client->request('GET', '/logout');

        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode(), $response->getContent());

        $this->assertTrue($response->headers->has('Location'), $response->headers);
        $this->assertStringEndsWith('/api/user/logout', $response->headers->get('Location'));
    }

    /**
     * @depends testLogoutRedirect
     */
    public function testLogout()
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/logout');

        $response = $client->getResponse();

        $userInfo = $this->assertJsonResponse($response, 200, true);

        $this->assertUserInfoObjStructure($userInfo);
        $this->assertUserInfoObjNoAuth($userInfo);
    }

    /**
     * @depends      testAnonymousOAuth
     * @dataProvider testAnonymousOAuth
     */
    public function testAnonymousSession($clientCredentials)
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info',
            ['access_token' => $clientCredentials->access_token]
        );

        $response = $client->getResponse();

        $userInfo = $this->assertJsonResponse($response, 200, true);

        $this->assertUserInfoObjStructure($userInfo);

        $this->assertTrue($userInfo->authenticated);
        $this->assertTrue($userInfo->authenticated_fully);
        $this->assertTrue($userInfo->authenticated_anonymously);
        $this->assertTrue($userInfo->role_anonymous);
        $this->assertFalse($userInfo->role_user);
        $this->assertFalse($userInfo->role_admin);
        $this->assertEmpty($userInfo->user);
        $this->assertNotEmpty($userInfo->client);

    }

}
