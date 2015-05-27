<?php

namespace Renatomefi\TestBundle\Rest;
use Symfony\Component\HttpFoundation\Response;

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
    public function assertJsonResponse($response, $statusCode = Response::HTTP_OK, $convert = false, $isArray = false)
    {

        if (is_array($statusCode)) {
            $this->assertTrue(in_array($response->getStatusCode(), $statusCode),
                'Excepted HTTP Status: ' . implode(',', $statusCode) . ' and Received: ' . $response->getStatusCode());
        } else {
            $this->assertEquals($statusCode, $response->getStatusCode(),
                'Excepted HTTP Status: ' . $statusCode . ' and Received: ' . $response->getStatusCode());
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

    /**
     * @inheritdoc
     */
    public function assertErrorResult($result)
    {
        $this->assertObjectHasAttribute('code', $result);
        $this->assertObjectHasAttribute('message', $result);
        $this->assertTrue(is_numeric($result->code));
        $this->assertNotEmpty($result->message);
    }
}