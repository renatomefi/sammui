<?php

namespace Renatomefi\TestBundle\MongoDB;

trait AssertMongoId
{
    public function assertMongoId($mongoId)
    {
        if (is_string($mongoId)) {
            $mongoId = new \MongoId($mongoId);
        }

        $this->assertTrue(($mongoId instanceof \MongoId));

        $this->assertNotNull($mongoId->getPID());
        $this->assertNotNull($mongoId->getTimestamp());
        $this->assertNotNull($mongoId->getInc());
        $this->assertNotNull($mongoId->getHostname());

        $this->assertEquals(24, strlen($mongoId->{'$id'}));

    }
}