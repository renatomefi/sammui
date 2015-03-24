<?php

namespace Renatomefi\TestBundle\Object;

/**
 * Class AssertObject
 * @package Renatomefi\TestBundle\Object
 */
trait AssertObject
{

    /**
     * @inheritdoc
     */
    public function assertObjectHasAttributes(array $attributes, $object)
    {
        foreach ($attributes as $attribute) {
            $this->assertObjectHasAttribute($attribute, $object, "Attribute '$attribute' not found.'");
        }
    }

    /**
     * @inheritdoc
     */
    public function assertObjectNotHasAttributes(array $attributes, $object)
    {
        foreach ($attributes as $attribute) {
            $this->assertObjectNotHasAttribute($attribute, $object, "Attribute '$attribute' not found.'");
        }
    }
}