<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class User
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
     * @ODM\String @ODM\Index(unique=true, order="asc")
     */
    protected $username;

    /**
     * @ODM\Date
     */
    protected $createdAt;

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
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
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
}
