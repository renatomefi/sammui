<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

trait ClientCredentials
{
    protected function assertClientCredentialsObjStructure($clientCredentials)
    {
        $this->assertObjectHasAttribute('access_token', $clientCredentials);
        $this->assertObjectHasAttribute('expires_in', $clientCredentials);
        $this->assertObjectHasAttribute('token_type', $clientCredentials);
        $this->assertObjectHasAttribute('scope', $clientCredentials);
    }
}