<?php

namespace Renatomefi\ApiBundle\Tests;

use Renatomefi\ApiBundle\Tests\Auth\UserInfo;
use Renatomefi\ApiBundle\Tests\Auth\ClientCredentials;
use Renatomefi\Test\RestTestCase;

class AuthTest extends RestTestCase
{

    use UserInfo, ClientCredentials;

    public function testAnonymousAuth()
    {
        $client = static::createClient();

        $client->request('GET', '/oauth/v2/token',
            [
                'client_id' => '54d2028ceabc88600a8b4567_qss71wwodiosk84gk4gwwk8s40k48wgg0cgkw8wwkwwgkcg44',
                'client_secret' => '5o808pbhkw84kcwggocc0ogos8c44socccgc0880koggoc08sk',
                'grant_type' => 'client_credentials'
            ], [], [
                'Accept' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $clientCredentials = $this->assertJsonResponse($response, 200, true);

        $this->assertClientCredentialsObjStructure($clientCredentials);
        $this->assertEquals(86, strlen($clientCredentials->access_token));
        $this->assertObjectNotHasAttribute('refresh_token', $clientCredentials);

        $this->assertEquals('bearer', $clientCredentials->token_type);

        return [[$clientCredentials]];
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

        $this->assertEquals(
            302, $response->getStatusCode(),
            $response->getContent()
        );

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
     * @depends      testAnonymousAuth
     * @dataProvider testAnonymousAuth
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
