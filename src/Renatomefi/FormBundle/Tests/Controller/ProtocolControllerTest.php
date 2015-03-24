<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Tests\Form\AssertForm;
use Renatomefi\FormBundle\Tests\Form\AssertFormInterface;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtilsInterface;
use Renatomefi\TestBundle\Object\AssertObject;
use Renatomefi\TestBundle\Object\AssertObjectInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManageControllerTest
 * @package Renatomefi\FormBundle\Tests\Controller
 */
class ProtocolControllerTest extends WebTestCase implements AssertRestUtilsInterface, AssertMongoUtilsInterface, AssertFormInterface, AssertObjectInterface
{

    use AssertMongoUtils, AssertForm, AssertRestUtils, AssertObject;

    /**
     * @var DocumentManager
     */
    protected $dm;

    protected static $formName = 'form-protocol-test';

    public function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->dm = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
    }

    /**
     * @return Form
     */
    public function testFormNew()
    {
        $form = new Form();
        $form->setName(static::$formName);

        $this->dm->persist($form);
        $this->dm->flush();

        return $form;
    }

    /**
     * @depends testFormNew
     * @param Form $form
     * @return Protocol
     */
    public function testPostProtocol($form)
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/form/protocol/' . $form->getId(), [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $protocol = $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true);

        $this->assertObjectHasAttributes(
            ['id', 'created_at', 'comment', 'user', 'non_user', 'form'], $protocol
        );

        $this->assertEquals($form->getId(), $protocol->form->id);

        return $protocol;
    }

    /**
     * @depends testFormNew
     */
    public function testGetProtocolNotFound()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/form/protocol/' . 'abc123', [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $this->assertJsonResponse($client->getResponse(), Response::HTTP_NOT_FOUND, true);
    }

    /**
     * @depends testPostProtocol
     * @param $protocol
     */
    public function testGetProtocol($protocol)
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/form/protocol/' . $protocol->id, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true);
    }

    /**
     * @depends testPostProtocol
     * @param $protocol
     */
    public function testGetProtocolsByForm($protocol)
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/form/protocol/forms/' . $protocol->form->id, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $protocols = $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true);
        $this->assertTrue((count($protocols) > 0));
    }

    /**
     * @depends testFormNew
     * @depends testGetProtocolNotFound
     * @depends testGetProtocol
     * @depends testGetProtocolsByForm
     */
    public function testDeleteForm()
    {
        $formRepo = $this->dm->getRepository('FormBundle:Form');

        $forms = $formRepo->findByName(static::$formName);

        if (count($forms) < 1) return;

        foreach ($forms as $form) {
            $this->dm->remove($form);
        }

        $this->dm->flush();
    }

}