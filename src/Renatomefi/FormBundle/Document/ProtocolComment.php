<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form Protocol comments
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class ProtocolComment
{
    /**
     * Document startup
     */
    public function __construct()
    {
        $this->setCreatedAt(new \MongoDate());
    }

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\Date
     */
    protected $createdAt;

    /**
     * @ODM\String
     */
    protected $body;

    /**
     * Get id
     *
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \MongoDate $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \MongoDate $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return string $body
     */
    public function getBody()
    {
        return $this->body;
    }
}
