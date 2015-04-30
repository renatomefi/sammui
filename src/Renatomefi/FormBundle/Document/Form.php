<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\Document
 */
class Form
{

    /**
     * Setup document
     */
    public function __construct()
    {
        $this->setCreatedAt(new \MongoDate());
        $this->pages = new ArrayCollection();
        $this->fields = new ArrayCollection();
    }

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\Date
     */
    protected $createdAt;

    /**
     * @ODM\EmbedMany(targetDocument="FormPage")
     * @var ArrayCollection
     */
    protected $pages;

    /**
     * @ODM\String
     */
    protected $template;

    /**
     * @ODM\ReferenceMany(targetDocument="FormField")
     */
    protected $fields;

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
     * Set template
     *
     * This template should match a path inside Resources/public/angular/views/form/pages/{{template}}
     * in order to provide the page's views
     * @param string $template
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return string $name
     */
    public function getTemplate()
    {
        return $this->template;
    }

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
     * Add page
     *
     * @param \Renatomefi\FormBundle\Document\FormPage $page
     */
    public function addPage(FormPage $page)
    {
        $this->pages[] = $page;
    }

    /**
     * Remove page
     *
     * @param \Renatomefi\FormBundle\Document\FormPage $page
     */
    public function removePage(FormPage $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection $pages
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add field
     *
     * @param \Renatomefi\FormBundle\Document\FormField $field
     */
    public function addField(FormField $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Remove field
     *
     * @param \Renatomefi\FormBundle\Document\FormField $field
     */
    public function removeField(FormField $field)
    {
        $this->fields->removeElement($field);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection $fields
     */
    public function getFields()
    {
        return $this->fields;
    }
}
