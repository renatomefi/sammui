<?php

namespace Renatomefi\TestBundle\Rest;

/**
 * Class AssertRestUtils
 * @package Renatomefi\TestBundle\Rest
 * @codeCoverageIgnore
 */
trait AssertRestUtils
{

    /**
     * @inheritdoc
     */
    public function assertJsonResponse($response, $statusCode = 200, $convert = false, $isArray = false)
    {

        if (is_array($statusCode)) {
            $this->assertTrue(in_array($response->getStatusCode(), $statusCode));
        } else {
            $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
        }

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );

        if (true === $convert) {
            $this->assertNotEmpty($response->getContent());

            $conversion = json_decode($response->getContent());

            if (false === $isArray) {
                $this->assertTrue(($conversion instanceof \stdClass), $response->getContent());
            } else {
                $this->assertTrue(is_array($conversion));
            }

            return $conversion;
        }
    }
}