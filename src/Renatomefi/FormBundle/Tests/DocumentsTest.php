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

namespace Renatomefi\FormBundle\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolComment;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentsTest extends WebTestCase
{

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->documentManager = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
    }

    public function testForm()
    {
        $form = new Form();
        $form->setName('test');

        $this->documentManager->persist($form);
        $this->documentManager->flush();

        $this->assertNotEmpty($form->getId());
        $this->assertNotEmpty($form->getCreatedAt());
        $this->assertNotEmpty($form->getName());

        return $form;
    }

    /**
     * @depends testForm
     * @param Form $form
     */
    public function testProtocol(Form $form)
    {
        $formRepo = $this->documentManager->getRepository('FormBundle:Form');
        $formObj = $formRepo->find($form->getId());

        $protocol = new Protocol();
        $protocol->setForm($formObj);
        $protocol->setFirstSaveDate(new \MongoDate());
        $protocol->setLastSaveDate(new \MongoDate());
        // Comment, User and NonUser are tested on ProtocolControllerTest

        $this->documentManager->persist($protocol);
        $this->documentManager->flush();

        $this->assertNotEmpty($protocol->getId());
        $this->assertNotEmpty($protocol->getCreatedAt());
        $this->assertNotEmpty($protocol->getFirstSaveDate());
        $this->assertNotEmpty($protocol->getLastSaveDate());
        $this->assertNotEmpty($protocol->getForm());
        $this->assertTrue(($protocol->getForm() instanceof Form));
        $this->assertEmpty($protocol->getOneComment('fake_123'));

        $this->documentManager->remove($formObj);
        $this->documentManager->remove($protocol);
        $this->documentManager->flush();
    }

    public function testProtocolComment()
    {
        $protocolComment = new ProtocolComment();
        $protocolComment->setBody('test');

        $this->documentManager->persist($protocolComment);
        $this->documentManager->flush();

        $this->assertNotEmpty($protocolComment->getId());
        $this->assertNotEmpty($protocolComment->getCreatedAt());
        $this->assertNotEmpty($protocolComment->getBody());

        $this->documentManager->remove($protocolComment);
        $this->documentManager->flush();
    }
}