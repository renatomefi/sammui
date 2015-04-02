<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form Protocol comments
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class ProtocolComment extends ProtocolEmbed
{
    /**
     * @ODM\String
     */
    protected $body;

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
