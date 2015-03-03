<?php

namespace Renatomefi\ApiBundle\Tests;

use Renatomefi\Test\RestTestCase;

class AuthTest extends RestTestCase
{

    protected function assertUserInfoObjStructure($userInfo)
    {
        $this->assertObjectHasAttribute('authenticated', $userInfo);
        $this->assertObjectHasAttribute('authenticated_fully', $userInfo);
        $this->assertObjectHasAttribute('authenticated_anonymously', $userInfo);
        $this->assertObjectHasAttribute('role_user', $userInfo);
        $this->assertObjectHasAttribute('role_admin', $userInfo);
        $this->assertObjectHasAttribute('role_anonymous', $userInfo);
        $this->assertObjectHasAttribute('client', $userInfo);
        $this->assertObjectHasAttribute('user', $userInfo);
    }

    protected function assertClientCredentialsObjStructure($clientCredentials)
    {
        $this->assertObjectHasAttribute('access_token', $clientCredentials);
        $this->assertObjectHasAttribute('expires_in', $clientCredentials);
        $this->assertObjectHasAttribute('token_type', $clientCredentials);
        $this->assertObjectHasAttribute('scope', $clientCredentials);
    }

    public function testEmptySession()
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info');

        $response = $client->getResponse();

        $userInfo = $this->assertJsonResponse($response, 200, true);

        $this->assertUserInfoObjStructure($userInfo);

        $this->assertTrue($userInfo->authenticated);
        $this->assertFalse($userInfo->authenticated_fully);
        $this->assertTrue($userInfo->authenticated_anonymously);
        $this->assertTrue($userInfo->role_anonymous);
        $this->assertFalse($userInfo->role_user);
        $this->assertFalse($userInfo->role_admin);
        $this->assertEmpty($userInfo->user);
        $this->assertEmpty($userInfo->client);

    }

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
