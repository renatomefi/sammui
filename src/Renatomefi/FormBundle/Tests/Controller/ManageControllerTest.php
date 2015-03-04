<?php

namespace Renatomefi\FormBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\AuthTest;
use Renatomefi\Test\RestTestCase;

class ManageControllerTest extends RestTestCase
{

    protected function setUp()
    {
        $auth = new AuthTest();

        return $auth->testPasswordOAuth()[0][0];
    }

    protected function assertFormStructure($form)
    {
        $this->assertObjectHasAttribute('id', $form);
        $this->assertObjectHasAttribute('name', $form);
        $this->assertObjectHasAttribute('created_at', $form);
    }

    public function testFormNew()
    {

        $clientCredentials = $this->setUp();

        if(!$clientCredentials) {
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
    }
}
