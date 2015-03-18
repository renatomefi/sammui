<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssertOAuth
 * @package Renatomefi\ApiBundle\Tests\Auth
 */
trait AssertOAuth
{

    /**
     * @inheritdoc
     */
    protected function assertOAuthRequired(Response $responseObj)
    {
        $response = json_decode($responseObj->getContent());

        $this->assertSame(401, $responseObj->getStatusCode());

        $this->assertTrue(($response instanceof \stdClass));

        $this->assertObjectHasAttribute('error', $response);
        $this->assertObjectHasAttribute('error_description', $response);

        $this->assertEquals('access_denied', $response->error);
        $this->assertEquals('OAuth2 authentication required', $response->error_description);
    }

}