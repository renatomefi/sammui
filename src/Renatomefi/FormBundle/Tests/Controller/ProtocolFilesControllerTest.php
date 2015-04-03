<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolFile;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProtocolFilesControllerTest
 * @package Renatomefi\FormBundle\Tests\Controller
 */
class ProtocolFilesControllerTest extends WebTestCase implements AssertRestUtilsInterface
{
    use AssertRestUtils;

    /**
     * @var DocumentManager
     */
    protected $dm;

    protected static $formName = 'form-protocol-file-upload-test';

    public function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->dm = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
    }

    /**
     * @param $protocolId
     * @param $fileParam
     * @return null|Response
     */
    protected function queryUpload($protocolId, $fileParam = false)
    {
        $client = static::createClient();

        $uri = static::$kernel->getContainer()
            ->get('router')->generate('renatomefi_form_protocolfiles_postupload', array(
                'protocolId' => $protocolId
            ));

        $files = [];
        if ($fileParam) {
            $file = new UploadedFile(__FILE__, get_class());
            $files['file'] = $file;
        }

        $client->request(Request::METHOD_POST, $uri, [], $files, [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        return $client->getResponse();
    }

    /**
     * @param $protocolId
     * @param $fileId
     * @param Array $params
     * @return null|Response
     */
    protected function queryFilePatch($protocolId, $fileId, $params = [])
    {
        $client = static::createClient();

        $uri = static::$kernel->getContainer()
            ->get('router')->generate('renatomefi_form_protocolfiles_patchupload', [
                'protocolId' => $protocolId,
                'fileId' => $fileId,
            ]);

        $client->request(Request::METHOD_PATCH, $uri, $params, [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        return $client->getResponse();
    }

    /**
     * @return Protocol
     */
    public function testCreateProtocol()
    {
        $form = new Form();
        $form->setName(static::$formName);

        $this->dm->persist($form);

        $protocol = new Protocol();
        $protocol->setForm($form);

        $this->dm->persist($protocol);

        $this->dm->flush();

        return $protocol;
    }

    /**
     * @depends testCreateProtocol
     */
    public function testUploadWithWrongProtocol()
    {
        $protocolId = 'test';
        $response = $this->queryUpload($protocolId);

        $result = $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND, true);

        $this->assertErrorResult($result);

        $this->assertEquals(sprintf('No Protocol found with id: "%s"', $protocolId), $result->message);

    }

    /**
     * @depends testCreateProtocol
     * @param Protocol $protocol
     */
    public function testUploadWithoutFile(Protocol $protocol)
    {
        $response = $this->queryUpload($protocol->getId());

        $result = $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST, true);

        $this->assertErrorResult($result);

        $this->assertEquals('You must provide a "file".', $result->message);
    }

    /**
     * @depends testCreateProtocol
     * @param Protocol $protocol
     * @return ProtocolFile
     */
    public function testUpload(Protocol $protocol)
    {
        $response = $this->queryUpload($protocol->getId(), true);

        $result = $this->assertJsonResponse($response, Response::HTTP_OK, true, true);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, count($result));
        $result = $result[0];
        $this->assertStringEndsWith($result->filename, get_class());
        $this->assertNotEmpty($result->mime_type);
        $this->assertNotEmpty($result->length);
        $this->assertEquals($protocol->getId(), $result->protocol->id);

        return $this->dm->getRepository('FormBundle:ProtocolFile')->find($result->id);
    }

    /**
     * @depends testUpload
     * @param ProtocolFile $file
     */
    public function testGetFile(ProtocolFile $file)
    {
        $client = static::createClient();

        $uri = static::$kernel->getContainer()
            ->get('router')->generate('renatomefi_form_protocolfiles_get', [
                'id' => $file->getId()
            ]);

        $client->request(Request::METHOD_GET, $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $response = $client->getResponse();

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/octet-stream'),
            $response->headers
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @depends testUpload
     */
    public function testFilePatchNotFound()
    {
        $response = $this->queryFilePatch('123', '123');

        $result = $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND, true);

        $this->assertErrorResult($result);

        $this->assertEquals(sprintf("No file with id '%s' found for protocol '%s'", '123', '123'), $result->message);
    }

    /**
     * @depends testUpload
     * @param ProtocolFile $file
     */
    public function testFilePatch(ProtocolFile $file)
    {
        $title = 'file_title';
        $description = 'file_description';

        $response = $this->queryFilePatch($file->getProtocol()->getId(), $file->getId(),
            [
                'title' => $title,
                'description' => $description
            ]);

        $result = $this->assertJsonResponse($response, Response::HTTP_OK, true);

        $this->assertEquals($title, $result->title);
        $this->assertEquals($description, $result->description);
    }

    /**
     * @depends testUpload
     * @depends testGetFile
     * @depends testFilePatch
     * @param ProtocolFile $file
     */
    public function testDeleteUpload(ProtocolFile $file)
    {
        $client = static::createClient();

        $uri = static::$kernel->getContainer()
            ->get('router')->generate('renatomefi_form_protocolfiles_deleteupload', [
                'protocolId' => $file->getProtocol()->getId(),
                'fileId' => $file->getId()
            ]);

        $client->request(Request::METHOD_DELETE, $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $result = $this->assertJsonResponse($client->getResponse(), Response::HTTP_OK, true, true);
        $this->assertEmpty($result);
    }

}
