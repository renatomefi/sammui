<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\Document
 */
class FormField extends ProtocolEmbed
{
    /**
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\Hash
     */
    protected $options;

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set options
     *
     * @param $options
     * @return self
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get options
     *
     * @return $options
     */
    public function getOptions()
    {
        return $this->options;
    }
}
