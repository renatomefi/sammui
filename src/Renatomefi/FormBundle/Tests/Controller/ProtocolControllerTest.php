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
use Renatomefi\UserBundle\DataFixtures\MongoDB\LoadUsers;
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
     * @param $protocolId
     * @param null $commentId
     * @param bool $removes
     * @return null|Response
     */
    protected function queryProtocolComment($protocolId, $commentId = null, $removes = false)
    {
        $client = static::createClient();

        $url = "/form/protocol/"
            . ((false === $removes) ? 'adds' : 'removes')
            . "/$protocolId/comment"
            . (($commentId) ? "s/$commentId" : '');

        $params = [];

        if (false === $removes) {
            $params['body'] = 'lorem ipsum';
        }

        $client->request(Request::METHOD_PATCH, $url, $params, [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        return $client->getResponse();
    }

    /**
     * @param $protocolId
     * @param $userName
     * @param bool $removes
     * @return null|Response
     */
    protected function queryProtocolUser($protocolId, $userName, $removes = false)
    {
        $client = static::createClient();

        $url = "/form/protocol/"
            . ((false === $removes) ? 'adds' : 'removes')
            . "/$protocolId/users/$userName";

        $client->request(Request::METHOD_PATCH, $url, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        return $client->getResponse();
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
     * @return array
     */
    public function testProtocolAddUser($protocol)
    {
        // Adding valid user
        $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME);
        // Adding again, should not count on final
        $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME);

        // Adding 1st invalid user
        $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME . '-fake-1');

        // Adding 2nd invalid user
        $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME . '-fake-2');
        // Adding again, should not count on final
        $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME . '-fake-2');

        // Adding 3rd invalid user and getting all of them
        $protocolUsers = $this->assertJsonResponse(
            $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME . '-fake-3'), Response::HTTP_OK, true
        );

        $this->assertEquals(1, count($protocolUsers->user));
        $this->assertEquals(3, count($protocolUsers->nonUser));

        // Testing valid User
        $this->assertNotNull($protocolUsers->user[0]->id);
        $this->assertNotNull($protocolUsers->user[0]->created_at);
        $this->assertEquals(LoadUsers::USER_USERNAME, $protocolUsers->user[0]->username);

        foreach ($protocolUsers->nonUser as $nonUser) {
            $this->assertNotNull($nonUser->id);
            $this->assertNotNull($nonUser->created_at);
            $this->assertStringStartsWith(LoadUsers::USER_USERNAME . '-fake-', $nonUser->username);
        }

        return $protocolUsers;
    }

    /**
     * @depends testPostProtocol
     * @depends testProtocolAddUser
     * @param $protocol
     * @param $protocolUsers
     */
    public function testProtocolRemoveUser($protocol, $protocolUsers)
    {
        //Deleting the only valid user
        $response = $this->assertJsonResponse(
            $this->queryProtocolUser($protocol->id, LoadUsers::USER_USERNAME, true), Response::HTTP_OK, true
        );
        $this->assertEquals(0, count($response->user));
        $this->assertEquals(3, count($response->nonUser));

        // Deleting all nonUser
        $i = 0;
        foreach ($protocolUsers->nonUser as $nonUser) {
            $response = $this->assertJsonResponse(
                $this->queryProtocolUser($protocol->id, $nonUser->username, true), Response::HTTP_OK, true
            );
            $i++;
            $this->assertEquals(count($protocolUsers->nonUser) - $i, count($response->nonUser));
            unset($response);
        }
    }

    /**
     * @depends testPostProtocol
     * @param $protocol
     * @return array
     */
    public function testProtocolAddComment($protocol)
    {
        // Adding 1st comment
        $this->queryProtocolComment($protocol->id);

        // Adding 2nd comment
        $this->queryProtocolComment($protocol->id);

        // Adding 3rs comment and getting all of them
        $comments = $this->assertJsonResponse(
            $this->queryProtocolComment($protocol->id), Response::HTTP_OK, true, true
        );

        $this->assertTrue((count($comments) === 3));

        foreach ($comments as $comment) {
            $this->assertNotNull($comment->id);
            $this->assertNotNull($comment->created_at);
            $this->assertEquals('lorem ipsum', $comment->body);
        }

        return $comments;
    }

    /**
     * @depends testPostProtocol
     * @param $protocol
     * @return array
     */
    public function testProtocolConclusion($protocol)
    {
        $client = static::createClient();
        $url = '/form/protocol/conclusions/' . $protocol->id;

        $client->request(Request::METHOD_PATCH, $url, [
            'conclusion' => 'conclusion'
        ], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @depends testPostProtocol
     * @depends testProtocolAddComment
     * @param $protocol
     * @param array $protocolComments
     */
    public function testProtocolRemoveComment($protocol, array $protocolComments)
    {
        $i = 0;
        foreach ($protocolComments as $comment) {
            $response = $this->assertJsonResponse(
                $this->queryProtocolComment($protocol->id, $comment->id, true), Response::HTTP_OK, true, true
            );
            $i++;
            $this->assertEquals(count($protocolComments) - $i, count($response));
            unset($response);
        }
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
     * @depends testProtocolAddComment
     * @depends testProtocolRemoveComment
     * @depends testProtocolConclusion
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