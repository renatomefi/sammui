<?php

namespace Renatomefi\UserBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Renatomefi\UserBundle\DataFixtures\MongoDB\LoadUsers;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerTest
 * @package Renatomefi\UserBundle\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase implements AssertRestUtilsInterface
{
    use OAuthClient, AssertRestUtils;

    protected $oAuthCredentials;

    public function setUp()
    {
        $this->oAuthCredentials = $this->getAdminCredentials();
    }

    public function testUserList()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/api/user/manage/users/info',
            ['access_token' => $this->oAuthCredentials->access_token], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $result = $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true, true);
        $this->assertNotEmpty($result);
        $this->assertEquals(LoadUsers::USER_USERNAME, $result[0]->username);
    }

    public function testUserGet()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/api/user/manage/users/' . LoadUsers::USER_USERNAME,
            ['access_token' => $this->oAuthCredentials->access_token], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $result = $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true);

        $this->assertEquals(LoadUsers::USER_USERNAME, $result->username);
    }

    public function testUserGetNotFound()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/api/user/manage/users/' . 'fake_user_123',
            ['access_token' => $this->oAuthCredentials->access_token], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $result = $this->assertJsonResponse($client->getResponse(), Response::HTTP_NOT_FOUND, true);

        $this->assertErrorResult($result);
    }
}
