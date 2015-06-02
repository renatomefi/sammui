<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Form
 * @package Renatomefi\FormBundle\Document
 * @ODM\EmbeddedDocument
 */
class ProtocolFieldValue extends ProtocolEmbed
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLastUpdated(new \MongoDate());
    }

    /**
     * @ODM\ReferenceOne(targetDocument="FormField")
     */
    protected $field;

    /**
     * @ODM\Raw
     */
    protected $value;

    /**
     * @ODM\Date
     */
    protected $lastUpdated;

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

    /**
     * Set value
     *
     * @param $value
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
     * @return $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the value in human readable format
     *
     * @return string
     */
    public function getValueInHR()
    {
        $value = $this->value;
        $opts = $this->getField()->getOptions();
        $isMulti = (count($opts) > 0);
        $isArray = is_array($value);

        $str = '';
        if (is_bool($value)) {
            $str = ($value === true) ? 'form-value-true' : 'form-value-false';
        } elseif ($isMulti) {
            if ($isArray) {
                foreach ($value as $k => $v) {
                    if ($v === true)
                        $str .= $opts[$k] . ',';
                }
                $str = rtrim($str, ',');
            } else {
                if (array_key_exists($value, $opts)) {
                    $str = $opts[$value];
                } else {
                    $str = $value;
                }
            }
        } elseif (null === $value) {
            $str = 'form-value-null';
        } else {
            $str = $value;
        }

        return $str;
    }

    /**
     * Set lastUpdated
     *
     * @param \MongoDate $lastUpdated
     * @return self
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \MongoDate $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

}
