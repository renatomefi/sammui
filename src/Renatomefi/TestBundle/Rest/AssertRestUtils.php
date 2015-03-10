<?php

namespace Renatomefi\TestBundle\Rest;

/**
 * @codeCoverageIgnore
 */
trait AssertRestUtils
{

    protected function assertJsonResponse($response, $statusCode = 200, $convert = false, $isArray = false)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
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