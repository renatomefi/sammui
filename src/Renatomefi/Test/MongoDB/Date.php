<?php

namespace Renatomefi\Test\MongoDB;

trait Date
{
    public function assertMongoDateFormat($dateObj)
    {
        $this->assertTrue(($dateObj instanceof \stdClass));
        $this->assertNotEmpty($dateObj->sec);
        $this->assertNotEmpty($dateObj->usec);

        $date = new \MongoDate($dateObj->sec, $dateObj->usec);
        $this->assertTrue(($date instanceof \MongoDate));
    }
}