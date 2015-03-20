<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface AssertOAuthInterface
 * @package Renatomefi\ApiBundle\Tests\Auth
 */
interface AssertOAuthInterface
{

    /**
     * Validates the structure of an OAuth error Response
     * @param Response $responseObj
     * @return mixed
     */
    public function assertOAuthError(Response $responseObj);

    /**
     * Validates a default response for a Required OAuth protected action
     * @param Response $responseObj
     * @return mixed
     */
    public function assertOAuthRequired(Response $responseObj);

    /**
     * Validates a default response for a Invalid Token OAuth protected action
     * @param Response $responseObj
     * @return mixed
     */
    public function assertOAuthInvalidToken(Response $responseObj);
}