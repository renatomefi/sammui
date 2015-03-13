<?php

namespace Renatomefi\TestBundle\MongoDB;

/**
 * Interface AssertMongoUtilsInterface
 * @package Renatomefi\TestBundle\MongoDB
 * @codeCoverageIgnore
 */
interface AssertMongoUtilsInterface
{
    /**
     * Validate for \MongoDate format
     *
     * @param mixed $dateObj
     */
    public function assertMongoDateFormat($dateObj);

    /**
     * @param \StdClass $response
     * @param String $duplicateKey
     */
    public function assertMongoDuplicateEntry(\StdClass $response, $duplicateKey = null);

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
    public function assertMongoDeleteFormat($deleteResponse, $assertDeleteQty = true);
}