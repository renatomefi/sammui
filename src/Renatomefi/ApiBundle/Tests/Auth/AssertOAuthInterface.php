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
     * Validates a default response for a Required OAuth protected action
     * @param Response $responseObj
     * @return mixed
     */
    public function assertOAuthRequired(Response $responseObj);
}