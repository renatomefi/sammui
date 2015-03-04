<?php

namespace Renatomefi\FormBundle\Tests\Form;

trait Form
{
    protected function assertFormStructure($form)
    {
        $this->assertObjectHasAttribute('id', $form);
        $this->assertObjectHasAttribute('name', $form);
        $this->assertObjectHasAttribute('created_at', $form);
    }
}