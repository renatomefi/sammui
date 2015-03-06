<?php

namespace Renatomefi\Test\MongoDB;

/**
 * @codeCoverageIgnore
 */
trait Utils
{
    /**
     * Validate for \MongoDate format
     *
     * @param mixed $dateObj
     */
    public function assertMongoDateFormat($dateObj)
    {
        if (is_array($dateObj)) $dateObj = (object)$dateObj;

        $this->assertTrue(($dateObj instanceof \stdClass), $dateObj);
        $this->assertNotEmpty($dateObj->sec);
        $this->assertNotEmpty($dateObj->usec);

        $date = new \MongoDate($dateObj->sec, $dateObj->usec);
        $this->assertTrue(($date instanceof \MongoDate));
    }

    /**
     * @param \StdClass $response
     * @param String $duplicateKey
     */
    public function assertMongoDuplicateEntry(\StdClass $response, $duplicateKey = null)
    {
        $this->assertObjectHasAttribute('code', $response);
        $this->assertEquals(409, $response->code);

        $this->assertObjectHasAttribute('message', $response);
        $this->assertStringStartsWith('Duplicate entry', $response->message);

        if ($duplicateKey)
            $this->assertStringEndsWith($duplicateKey, $response->message);
    }

    /**
     * Validate for typical MongoDB Delete json response
     * {
     *   "n":1,
     *   "connectionId":47,
     *   "ok":1
     * }
     *
     * @param mixed $deleteResponse
     * @param bool $assertDeleteQty Should test for number of deletions made by Mongo?
     * @throws \Exception
     */
    public function assertMongoDeleteFormat($deleteResponse, $assertDeleteQty = true)
    {
        if (is_array($deleteResponse)) $deleteResponse = (object)$deleteResponse;

        if ($deleteResponse instanceof \StdClass) {
            $this->assertObjectHasAttribute('n', $deleteResponse);
            $this->assertObjectHasAttribute('connectionId', $deleteResponse);
            $this->assertObjectHasAttribute('ok', $deleteResponse);

            $this->assertTrue(($deleteResponse->ok == 1));
            $this->assertTrue(($deleteResponse->connectionId >= 1));

            if (TRUE === $assertDeleteQty)
                $this->assertTrue(($deleteResponse->n >= 1));

        } else {
            throw new \Exception('Invalid MongoDB deleteResponse data');
        }
    }

}