<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManageControllerTest extends WebTestCase
{
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testLangs()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/l10n/manage/langs');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }

    public function createLang()
    {
        $client   = static::createClient();
        $crawler  = $client->request('POST', '/l10n/manage/langs/unit-test');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }
}
