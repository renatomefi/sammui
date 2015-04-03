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
use Renatomefi\FormBundle\Document\ProtocolFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DocumentsTest
 * @package Renatomefi\FormBundle\Tests
 */
class DocumentsTest extends WebTestCase
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

    /**
     * Create a form in order to continue the tests
     * @return Form
     */
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
     * Test Protocol Document
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
        $protocol->setConclusion('Conclusion');

        $file = new ProtocolFile();
        $upload = new UploadedFile(__FILE__, get_class());
        $file->setFile($upload);
        $file->setTitle('file_title');
        $file->setDescription('file_description');
        $file->setFilename($upload->getClientOriginalName());
        $file->setMimeType($upload->getClientMimeType());
        $file->setUploadDate(new \MongoDate());
        $file->setLength($upload->getSize());
        $file->setMd5('123');
        $file->setChunkSize(123);
        $file->setProtocol($protocol);

        $protocol->addFile($file);

        // Comment, User and NonUser are tested on ProtocolControllerTest
        $this->documentManager->persist($file);
        $this->documentManager->persist($protocol);
        $this->documentManager->flush();
//        $this->documentManager->clear();

        $protocol = $this->documentManager->getRepository('FormBundle:Protocol')->find($protocol->getId());

        $this->assertNotEmpty($protocol->getId());
        $this->assertNotEmpty($protocol->getCreatedAt());
        $this->assertNotEmpty($protocol->getFirstSaveDate());
        $this->assertNotEmpty($protocol->getLastSaveDate());
        $this->assertNotEmpty($protocol->getForm());
        $this->assertEquals('Conclusion', $protocol->getConclusion());
        $this->assertTrue(($protocol->getForm() instanceof Form));
        $this->assertEmpty($protocol->getOneComment('fake_123'));

        // Testing ProtocolFile
//        $this->assertInstanceOf(get_class(new ArrayCollection()), $protocol->getFile());

        /** @var ProtocolFile $protocolFile */
        $protocolFile = $protocol->getFile()->first();
        $this->assertEquals('file_title', $protocolFile->getTitle());
        $this->assertEquals('file_description', $protocolFile->getDescription());
        $this->assertNotNull($protocolFile->getUploadDate());
        $this->assertNotNull($protocolFile->getLength());
        $this->assertNotNull($protocolFile->getChunkSize());
        $this->assertNotNull($protocolFile->getMd5());

        $protocol->removeFile($file);
        $this->documentManager->remove($protocol);
        $this->documentManager->remove($formObj);
        $this->documentManager->flush();
    }

    /**
     * Test Protocol Comment embed Document
     */
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