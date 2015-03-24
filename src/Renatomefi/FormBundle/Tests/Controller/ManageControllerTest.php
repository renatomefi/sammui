<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\FormBundle\Tests\Form\AssertForm;
use Renatomefi\FormBundle\Tests\Form\AssertFormInterface;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtilsInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManageControllerTest
 * @package Renatomefi\FormBundle\Tests\Controller
 */
class ManageControllerTest extends WebTestCase implements OAuthClientInterface, AssertRestUtilsInterface, AssertMongoUtilsInterface, AssertFormInterface
{

    use AssertMongoUtils, AssertForm, AssertRestUtils, OAuthClient;

    /**
     * @var
     */
    protected $_oAuthCredentials;

    /**
     * Setup Credentials
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->_oAuthCredentials = $this->getAdminCredentials();

        if (!$this->_oAuthCredentials) {
            $this->markTestSkipped('No credentials to Login');
        }
    }

    /**
     * @param string $method
     * @param bool $assertJson
     * @param array $params
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    protected function queryFormManage($method = Request::METHOD_GET, $assertJson = true, $params = [])
    {
        $client = static::createClient();

        $defaultParams = [
            'access_token' => $this->_oAuthCredentials->access_token
        ];

        if (count($params) > 0) $defaultParams = array_merge($params, $defaultParams);

        $client->request($method, '/form/manage', $defaultParams, [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, Response::HTTP_OK, true) : $response;
    }

    /**
     * @param null $urlSuffix
     * @param array $params
     * @param int $httpCode
     * @return mixed
     */
    protected function queryFormManageGet($urlSuffix = null, $params = [], $httpCode = Response::HTTP_OK)
    {
        $client = static::createClient();

        $urlSuffix = ($urlSuffix !== null) ? '/' . $urlSuffix : '';

        $client->request(Request::METHOD_GET, '/form/manage' . $urlSuffix, $params, [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        return $this->assertJsonResponse($client->getResponse(), $httpCode, true);
    }

    /**
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    public function testFormNew()
    {
        $form = $this->queryFormManage(Request::METHOD_POST, true, [
            'name' => 'Form Test PHPUnit: ' . time()
        ]);

        $this->assertFormStructure($form);
        $this->assertNotEmpty($form->id);
        $this->assertStringStartsWith('Form Test PHPUnit: ', $form->name);

        $this->assertMongoDateFormat($form->created_at);

        return $form;
    }

    /**
     * @depends testFormNew
     *
     * @param $form
     */
    public function testFormGet($form)
    {
        $formGet = $this->queryFormManageGet($form->id);

        $this->assertFormStructure($formGet);
        $this->assertEquals($form->name, $formGet->name);
    }

    /**
     * @depends testFormNew
     *
     * @param $form
     */
    public function testFormList($form)
    {
        $client = static::createClient();

        $client->request('GET', '/form/manage/list/all', [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $formList = $this->assertJsonResponse($client->getResponse(), 200, true, true);

        $this->assertTrue((count($formList) >= 1));

        $foundForm = false;
        foreach ($formList as $f) {
            if ($f->name == $form->name) $foundForm = true;
        }
        $this->assertTrue($foundForm, 'Didn\'t find the form on the list');
    }

    /**
     * @depends testFormNew
     * @depends testFormGet
     * @depends testFormList
     *
     * @param $form
     */
    public function testFormDelete($form)
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $formRepo = $kernel->getContainer()->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        $delete = $formRepo->createQueryBuilder()
            ->remove()
            ->field('id')->equals($form->id)
            ->getQuery()
            ->execute();

        $this->assertMongoDeleteFormat($delete);
    }

    /**
     * @depends testFormNew
     * @depends testFormDelete
     *
     * @param $form
     */
    public function testFormNotFound($form)
    {
        $notFound = $this->queryFormManageGet($form->id, [], Response::HTTP_NOT_FOUND);

        $baseFormat = 'No form found with id: "%s"';
        $this->assertStringMatchesFormat($baseFormat, $notFound->message);
        $this->assertSame(sprintf($baseFormat, $form->id), $notFound->message);
    }

}
