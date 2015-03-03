<?php

namespace Renatomefi\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTest extends WebTestCase
{

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

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

        $this->assertJsonResponse($response);

        $this->assertNotEmpty($response->getContent());

        $contentObj = json_decode($response->getContent());

        $this->assertTrue(($contentObj instanceof \stdClass));

        $this->assertObjectHasAttribute('access_token', $contentObj);
        $this->assertObjectHasAttribute('expires_in', $contentObj);
        $this->assertObjectHasAttribute('token_type', $contentObj);
        $this->assertObjectHasAttribute('scope', $contentObj);
        $this->assertObjectNotHasAttribute('refresh_token', $contentObj);

        $this->assertEquals('bearer', $contentObj->token_type);

    }

    /**
     * @depends testAnonymousAuth
     */
    public function testSession()
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/info');

        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        $this->assertNotEmpty($response->getContent());

        $contentObj = json_decode($response->getContent());

        $this->assertTrue(($contentObj instanceof \stdClass), $response->getContent());

        $this->assertUserInfoObjStructure($contentObj);

    }

}
