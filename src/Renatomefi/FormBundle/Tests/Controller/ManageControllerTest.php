<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\AuthTest;
use Renatomefi\FormBundle\Tests\Form\Form;
use Renatomefi\TestBundle\MongoDB\MongoUtils;
use Renatomefi\TestBundle\Rest\RestUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManageControllerTest extends WebTestCase
{

    use MongoUtils, Form, RestUtils;

    protected $_oAuthCredentials;

    protected function setUp()
    {
        $auth = new AuthTest();

        $this->_oAuthCredentials = $auth->testPasswordOAuth()[0][0];
    }

    public function testFormList()
    {
        $this->markTestIncomplete('Need to create a test');
    }

    public function testFormGet()
    {
        $this->markTestIncomplete('Need to create a test');
    }

    public function testFormNew()
    {

        $clientCredentials = $this->_oAuthCredentials;
        if (!$clientCredentials) {
            $this->markTestSkipped('No credentials to Login');
        }

        $client = static::createClient();

        $client->request('POST', '/form/manage',
            [
                'access_token' => $clientCredentials->access_token,
                'name' => 'Form Test PHPUnit: ' . time()
            ], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $client->getResponse();

        $form = $this->assertJsonResponse($response, 200, true);

        $this->assertFormStructure($form);
        $this->assertNotEmpty($form->id);
        $this->assertStringStartsWith('Form Test PHPUnit: ', $form->name);

        $this->assertMongoDateFormat($form->created_at);

    }
}
