<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Renatomefi\FormBundle\Document\Form;

/**
 * Class Protocol
 * @package Renatomefi\FormBundle\Document
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
    protected $createdAt;

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
     * Set firstSaveDate
     *
     * @param \MongoDate $firstSaveDate
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
     * @return \MongoDate $firstSaveDate
     */
    public function getFirstSaveDate()
    {
        return $this->firstSaveDate;
    }

    /**
     * Set lastSaveDate
     *
     * @param \MongoDate $lastSaveDate
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
     * @return \MongoDate $lastSaveDate
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
    public function setForm(Form $form)
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
