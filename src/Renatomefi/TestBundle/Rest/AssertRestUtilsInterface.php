<?php

namespace Renatomefi\TestBundle\Rest;

/**
 * Interface AssertRestUtilsInterface
 * @package Renatomefi\TestBundle\Rest
 * @codeCoverageIgnore
 */
interface AssertRestUtilsInterface
{
    /**
     * Verify a Json Response and Converts it to PHP Object
     * @param $response
     * @param mixed $statusCode Desired HTTP Status Code
     * @param bool $convert Convert Json to StdClass
     * @param bool $isArray If your Json is an Array you should inform
     * @return mixed
     */
    public function assertJsonResponse($response, $statusCode = 200, $convert = false, $isArray = false);
}