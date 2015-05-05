<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class FormFieldDepends
{

    /**
     * Setting up properties
     *
     * @param FormField $field
     * @param Array|mixed $customValue
     */
    public function __construct(FormField $field = null, $customValue = null)
    {
        $this->customValue = [];

        if (null !== $field) {
            $this->setField($field);

            if (null !== $customValue) {
                if (is_array($customValue)) {
                    $this->customValue = $customValue;
                } else {
                    $this->addCustomValue($customValue);
                }
            }
        }
    }

    /**
     * @var FormField
     * @ODM\ReferenceOne(targetDocument="FormField")
     */
    protected $field;

    /**
     * @ODM\Collection
     */
    protected $customValue;

    /**
     * Set field
     *
     * @param \Renatomefi\FormBundle\Document\FormField $field
     * @return self
     */
    public function setField(FormField $field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Get field
     *
     * @return \Renatomefi\FormBundle\Document\FormField $field
     */
    public function getField()
    {
        return $this->field;
    }


    public function addCustomValue($customValue)
    {
        if (!$this->field) {
            throw new DocumentNotFoundException('Please, set the field before using customValues: self::setField(FormField)');
        }

        $fieldOptions = $this->field->getOptions();
        if (count($fieldOptions) > 0 && !array_key_exists($customValue, $fieldOptions)) {
            throw new DocumentNotFoundException('No reference for options found with: ' .
                $customValue . ' at field: ' . $this->field->getName());
        }

        $this->customValue[] = $customValue;
        return $this;
    }

    /**
     * Set customValue
     *
     * @param Array $customValues
     * @return self
     */
    public function setCustomValue(Array $customValues)
    {
        $this->customValue = $customValues;
        return $this;
    }

    /**
     * Get customValue
     *
     * @return Array $customValue
     */
    public function getCustomValue()
    {
        return $this->customValue;
    }
}
