<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

/**
 * Interface AssertClientCredentialsInterface
 * @package Renatomefi\ApiBundle\Tests\Auth
 * @codeCoverageIgnore
 */
interface AssertClientCredentialsInterface
{
    /**
     * @param $clientCredentials
     * @return mixed
     */
    public function assertClientCredentialsObjStructure($clientCredentials);

    /**
     * @param $clientCredentials
     * @param string $tokenName
     * @return mixed
     */
    public function assertClientCredentialsToken($clientCredentials, $tokenName = 'access_token');
}