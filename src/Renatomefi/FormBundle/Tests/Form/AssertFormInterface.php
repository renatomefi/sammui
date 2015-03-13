<?php

namespace Renatomefi\FormBundle\Tests\Form;

/**
 * Interface AssertFormInterface
 * @package Renatomefi\FormBundle\Tests\Form
 * @codeCoverageIgnore
 */
interface AssertFormInterface
{
    /**
     * @param $form
     * @return mixed
     */
    public function assertFormStructure($form);
}