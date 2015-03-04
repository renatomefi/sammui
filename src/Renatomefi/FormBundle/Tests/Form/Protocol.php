<?php

namespace Renatomefi\FormBundle\Tests\Form;

trait Protocol
{
    protected function assertFormProtocolStructure($form)
    {
        $this->assertObjectHasAttribute('id', $form);
        $this->assertObjectHasAttribute('created_at', $form);
        $this->assertObjectHasAttribute('form', $form);
    }
}