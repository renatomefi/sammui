<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

/**
 * @codeCoverageIgnore
 */
trait AssertClientCredentials
{
    protected function assertClientCredentialsObjStructure($clientCredentials)
    {
        $this->assertObjectHasAttribute('access_token', $clientCredentials);
        $this->assertObjectHasAttribute('expires_in', $clientCredentials);
        $this->assertObjectHasAttribute('token_type', $clientCredentials);
        $this->assertObjectHasAttribute('scope', $clientCredentials);
    }

    protected function assertClientCredentialsToken($clientCredentials, $tokenName = 'access_token')
    {
        $this->assertObjectHasAttribute($tokenName, $clientCredentials);
        $this->assertNotEmpty($clientCredentials->{$tokenName}, $clientCredentials->{$tokenName});
        $this->assertEquals(86, strlen($clientCredentials->{$tokenName}), 'token size does not match');
    }
}