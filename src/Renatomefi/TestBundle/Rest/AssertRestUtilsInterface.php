<?php

namespace Renatomefi\TestBundle\Rest;

/**
 * @codeCoverageIgnore
 */
interface AssertRestUtilsInterface
{
    public function assertJsonResponse($response, $statusCode = 200, $convert = false, $isArray = false);
}