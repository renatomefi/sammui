<?php

namespace Renatomefi\FormBundle\Tests\Form;

/**
 * Interface AssertProtocolInterface
 * @package Renatomefi\FormBundle\Tests\Form
 * @codeCoverageIgnore
 */
interface AssertProtocolInterface
{
    /**
     * @param $form
     * @return mixed
     */
    public function assertFormProtocolStructure($form);
}