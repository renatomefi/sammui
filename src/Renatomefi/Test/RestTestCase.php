<?php

namespace Renatomefi\Test;

class RestTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected function assertJsonResponse($response, $statusCode = 200, $convert = false)
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
            $obj = json_decode($response->getContent());
            $this->assertTrue(($obj instanceof \stdClass), $response->getContent());

            return $obj;
        }
    }
}