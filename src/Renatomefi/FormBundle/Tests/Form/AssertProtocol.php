<?php

namespace Renatomefi\FormBundle\Tests\Form;

/**
 * Class AssertProtocol
 * @package Renatomefi\FormBundle\Tests\Form
 * @codeCoverageIgnore
 */
trait AssertProtocol
{
    /**
     * @inheritdoc
     */
    protected function assertFormProtocolStructure($form)
    {
        $this->assertObjectHasAttribute('id', $form);
        $this->assertObjectHasAttribute('created_at', $form);
        $this->assertObjectHasAttribute('form', $form);
    }
}