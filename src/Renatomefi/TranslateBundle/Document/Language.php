<?php

namespace Renatomefi\TranslateBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * Class Language
 * @ODM\Document
 * @package Renatomefi\TranslateBundle\Document
 */
class Language
{

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\Date
     */
    protected $lastUpdate;

    /**
     * @ODM\String @ODM\Index(unique=true, order="asc")
     */
    protected $key;

    /**
     * @ODM\ReferenceMany(targetDocument="Translation", mappedBy="language")
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

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
     * Set lastUpdate
     *
     * @param \MongoDate $lastUpdate
     * @return self
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \MongoDate $lastUpdate
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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
     * Add translation
     *
     * @param \Renatomefi\TranslateBundle\Document\Translation $translation
     */
    public function addTranslation(Translation $translation)
    {
        $this->translations[] = $translation;
    }

    /**
     * Remove translation
     *
     * @param \Renatomefi\TranslateBundle\Document\Translation $translation
     */
    public function removeTranslation(Translation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection $translations
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
