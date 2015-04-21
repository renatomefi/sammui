<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class ProtocolFieldValue extends ProtocolEmbed
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLastUpdated(new \MongoDate());
    }
    
    /**
     * @ODM\ReferenceOne(targetDocument="FormField")
     */
    protected $field;

    /**
     * @ODM\Field
     */
    protected $value;

    /**
     * @ODM\Date
     */
    protected $lastUpdated;

    /**
     * Set field
     *
     * @param \Renatomefi\FormBundle\Document\FormField $field
     * @return self
     */
    public function setField(FormField $field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Get field
     *
     * @return \Renatomefi\FormBundle\Document\FormField $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set lastUpdated
     *
     * @param \MongoDate $lastUpdated
     * @return self
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \MongoDate $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

}
