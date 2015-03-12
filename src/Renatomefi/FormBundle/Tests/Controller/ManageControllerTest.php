<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\FormBundle\Tests\Form\AssertForm;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;

class ManageControllerTest extends WebTestCase implements OAuthClientInterface, AssertRestUtilsInterface
{

    use AssertMongoUtils, AssertForm, AssertRestUtils, OAuthClient;

    protected $_oAuthCredentials;

    protected function setUp()
    {
        $this->_oAuthCredentials = $this->getAdminCredentials();

        if (!$this->_oAuthCredentials) {
            $this->markTestSkipped('No credentials to Login');
        }
    }

    protected function queryFormManage($method = 'GET', $assertJson = true, $params = array())
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

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, 200, true) : $response;
    }

    public function testFormNew()
    {
        $form = $this->queryFormManage('POST', true, [
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
    public function testFormDuplicate($form)
    {
        $this->markTestIncomplete('There is no constraint in form names');
    }

    /**
     * @depends testFormNew
     *
     * @param $form
     */
    public function testFormGet($form)
    {
        $client = static::createClient();

        $client->request('GET', '/form/manage/' . $form->id, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $formGet = $this->assertJsonResponse($client->getResponse(), 200, true);

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
        $this->markTestIncomplete('There is not delete in forms API');
    }

}
