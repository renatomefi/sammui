<?php

namespace Renatomefi\TranslateBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Translation
{

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\ReferenceOne(targetDocument="Language")
     */
    protected $language;

    /**
     * @ODM\String
     */
    protected $key;

    /**
     * @ODM\String
     */
    protected $value;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set language
     *
     * @param Renatomefi\TranslateBundle\Document\Language $language
     * @return self
     */
    public function setLanguage(\Renatomefi\TranslateBundle\Document\Language $language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return Renatomefi\TranslateBundle\Document\Language $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
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
}
