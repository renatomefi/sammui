<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\Test\RestTestCase;

class ManageControllerTest extends RestTestCase
{

    protected function assertLangStructure($langObj)
    {
        $this->assertObjectHasAttribute('id', $langObj);
        $this->assertObjectHasAttribute('last_update', $langObj);
        $this->assertObjectHasAttribute('key', $langObj);
        $this->assertObjectHasAttribute('translations', $langObj);
    }

    public function testLangCreate()
    {
        $client = static::createClient();

        $newLangName = 'unit-test-' . microtime();

        $client->request('POST', '/l10n/manage/langs/' . $newLangName,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $lang = $this->assertJsonResponse($response, 200, true);

        $this->assertLangStructure($lang);
        $this->assertEquals($newLangName, $lang->key);

        return [[$lang]];
    }

    /**
     * @depends      testLangCreate
     * @dataProvider testLangCreate
     */
    public function testLangDuplicate($lang)
    {
        $client = static::createClient();

        $client->request('POST', '/l10n/manage/langs/' . $lang->key,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $duplicate = $this->assertJsonResponse($response, 409, true);

        $this->assertObjectHasAttribute('code', $duplicate);
        $this->assertObjectHasAttribute('message', $duplicate);
        $this->assertStringStartsWith('Duplicate entry', $duplicate->message);
        $this->assertStringEndsWith($lang->key, $duplicate->message);
    }

    /**
     * @depends      testLangDuplicate
     * @dataProvider testLangCreate
     */
    public function testLangGet($lang)
    {
        $client = static::createClient();

        $client->request('GET', '/l10n/manage/langs/' . $lang->key,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $langGet = $this->assertJsonResponse($response, 200, true);

        $this->assertLangStructure($langGet);
        $this->assertEquals($lang->key, $langGet->key);
    }

    /**
     * @depends testLangCreate
     */
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

    /**
     * @depends      testLangGet
     * @dataProvider testLangCreate
     */
    public function testLangDelete($lang)
    {
        $this->markTestIncomplete('Delete for lang does not exists');
    }

}