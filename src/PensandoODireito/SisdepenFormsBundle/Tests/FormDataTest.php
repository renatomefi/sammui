<?php

/*
 * This file is part of sammui project.
 *
 * For the full copyright and license information, please
 * view the LICENSE file that was distributed with this
 * source code.
 *
 * Este arquivo faz parte do projeto sammui.
 *
 * Para acesso completo à licença e copyright, acesse o
 * arquivo LICENSE na raiz do projeto.
 *
 * (c) PensandooDireito SAL/MJ <https://github.com/pensandoodireito>
 * (c) Renato Mendes Figueiredo <renato@renatomefi.com.br>
 */

namespace PensandoODireito\SisdepenFormsBundle\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use PensandoODireito\SisdepenFormsBundle\DataFixtures\MongoDB\LoadForm;
use PensandoODireito\SisdepenFormsBundle\DataFixtures\MongoDB\LoadFormFields;
use Renatomefi\FormBundle\Document\Form;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class FormDataTest
 * @package PensandoODireito\SisdepenFormsBundle\Tests
 */
class FormDataTest extends KernelTestCase
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * Setup MongoDB DocumentManager
     */
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->documentManager = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
    }

    public function testSisdepenForm()
    {
        $formRepo = $this->documentManager->getRepository('FormBundle:Form');

        /** @var Form $form */
        $form = $formRepo->findOneByName(LoadForm::NAME);

        $this->assertObjectHasAttribute('name', $form);
        $this->assertEquals(LoadForm::NAME, $form->getName());
        $this->assertEquals(count(LoadForm::$pages), count($form->getPages()));

        return $form;
    }

    /**
     * @depends testSisdepenForm
     * @param Form $form
     */
    public function testSisdepenFormFields(Form $form)
    {
        $this->assertEquals(count(LoadFormFields::$fields), count($form->getFields()));
    }
}