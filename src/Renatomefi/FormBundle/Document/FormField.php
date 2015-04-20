<?php

namespace Renatomefi\FormBundle\Document;

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
     * @ODM\ReferenceOne(targetDocument="Form", inversedBy="fields")
     */
    protected $form;

    /**
     * @ODM\Field
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
     * Set form
     *
     * @param \Renatomefi\FormBundle\Document\Form $form
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
     * @return \Renatomefi\FormBundle\Document\Form $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set options
     *
     * @param string $options
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
     * @return string $options
     */
    public function getOptions()
    {
        return $this->options;
    }


}
