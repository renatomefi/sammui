<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\Test\RestTestCase;

class ManageControllerTest extends RestTestCase
{
    public function testLangsList()
    {
        $client = static::createClient();
        $client->request('GET', '/l10n/manage/langs',
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $langs = $this->assertJsonResponse($response, 200, true, true);
    }

    public function testLangCreate()
    {
        $client = static::createClient();
        $client->request('POST', '/l10n/manage/langs/unit-test',
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }
}
