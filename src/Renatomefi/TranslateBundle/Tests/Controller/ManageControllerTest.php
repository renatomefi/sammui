<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\Test\RestTestCase;

class ManageControllerTest extends RestTestCase
{
    public function testLangs()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/l10n/manage/langs');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }

    public function createLang()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/l10n/manage/langs/unit-test');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }
}
