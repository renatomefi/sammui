<?php

namespace Renatomefi\FormBundle\Tests\Form;

trait AssertProtocol
{
    protected function assertFormProtocolStructure($form)
    {
        $this->assertObjectHasAttribute('id', $form);
        $this->assertObjectHasAttribute('created_at', $form);
        $this->assertObjectHasAttribute('form', $form);
    }
}