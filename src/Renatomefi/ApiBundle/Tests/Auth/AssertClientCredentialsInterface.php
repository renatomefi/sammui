<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

/**
 * @codeCoverageIgnore
 */
interface AssertClientCredentialsInterface
{
    public function assertClientCredentialsObjStructure($clientCredentials);

    public function assertClientCredentialsToken($clientCredentials, $tokenName = 'access_token');
}