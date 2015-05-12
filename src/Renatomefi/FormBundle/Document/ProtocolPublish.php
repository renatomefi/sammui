<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Renatomefi\UserBundle\Document\User;

/**
 * Class ProtocolPublish
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class ProtocolPublish extends ProtocolEmbed
{
    /**
     * @ODM\ReferenceOne(targetDocument="Renatomefi\UserBundle\Document\User", simple="true")
     * @var User
     */
    protected $user;

    /**
     * @ODM\Boolean
     */
    protected $locked;

    /**
     * Set user
     *
     * @param \Renatomefi\UserBundle\Document\User $user
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Renatomefi\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return self
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean $locked
     */
    public function getLocked()
    {
        return $this->locked;
    }
}
