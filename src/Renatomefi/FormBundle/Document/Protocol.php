<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Protocol
{

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\Date
     */
    protected $creationDate;

    /**
     * @ODM\Date
     */
    protected $firstSaveDate;

    /**
     * @ODM\Date
     */
    protected $lastSaveDate;

    /**
     * @ODM\ReferenceOne(targetDocument="Form")
     */
    protected $form;


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
     * Set creationDate
     *
     * @param date $creationDate
     * @return self
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return date $creationDate
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set firstSaveDate
     *
     * @param date $firstSaveDate
     * @return self
     */
    public function setFirstSaveDate($firstSaveDate)
    {
        $this->firstSaveDate = $firstSaveDate;
        return $this;
    }

    /**
     * Get firstSaveDate
     *
     * @return date $firstSaveDate
     */
    public function getFirstSaveDate()
    {
        return $this->firstSaveDate;
    }

    /**
     * Set lastSaveDate
     *
     * @param date $lastSaveDate
     * @return self
     */
    public function setLastSaveDate($lastSaveDate)
    {
        $this->lastSaveDate = $lastSaveDate;
        return $this;
    }

    /**
     * Get lastSaveDate
     *
     * @return date $lastSaveDate
     */
    public function getLastSaveDate()
    {
        return $this->lastSaveDate;
    }

    /**
     * Set form
     *
     * @param Renatomefi\FormBundle\Document\Form $form
     * @return self
     */
    public function setForm(\Renatomefi\FormBundle\Document\Form $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get form
     *
     * @return Renatomefi\FormBundle\Document\Form $form
     */
    public function getForm()
    {
        return $this->form;
    }
}
