<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\Test\MongoDB\Date;
use Renatomefi\Test\RestTestCase;
use Renatomefi\TranslateBundle\Tests\Lang;

class ManageControllerTest extends RestTestCase
{

    use Date, Lang;

    const LANG = 'unit-test';

    protected function queryLangManage($method = 'GET', $assertJson = true)
    {
        $client = static::createClient();

        $client->request($method, '/l10n/manage/langs/' . static::LANG,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, 200, true) : $response;
    }

    public function testLangCreate()
    {
        $lang = $this->queryLangManage('POST');

        $this->assertLangStructure($lang);
        $this->assertEquals(static::LANG, $lang->key);
        $this->assertMongoDateFormat($lang->last_update);
    }

    /**
     * @depends      testLangCreate
     */
    public function testLangDuplicate()
    {
        $response = $this->queryLangManage('POST', false);

        $duplicate = $this->assertJsonResponse($response, 409, true);

        $this->assertObjectHasAttribute('code', $duplicate);
        $this->assertObjectHasAttribute('message', $duplicate);
        $this->assertStringStartsWith('Duplicate entry', $duplicate->message);
        $this->assertStringEndsWith(self::LANG, $duplicate->message);
    }

    /**
     * @depends      testLangCreate
     */
    public function testLangGet()
    {
        $langGet = $this->queryLangManage();

        $this->assertLangStructure($langGet);
        $this->assertEquals(self::LANG, $langGet->key);
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

        $this->assertTrue((count($langs) >= 1));

        $foundLang = false;
        foreach ($langs as $lang) {
            if ($lang->key == static::LANG) $foundLang = true;
        }
        $this->assertTrue($foundLang, 'Didn\'t find the lang on langs list');
    }

    /**
     * @depends      testLangGet
     * @depends      testLangsList
     * @depends      testLangDuplicate
     */
    public function testLangDelete()
    {
        $langDelete = $this->queryLangManage('DELETE');

        // Generic MongoDB delete response
        $this->assertObjectHasAttribute('n', $langDelete);
        $this->assertObjectHasAttribute('connectionId', $langDelete);
        $this->assertObjectHasAttribute('ok', $langDelete);

        // Success delete
        $this->assertTrue(($langDelete->n > 0));
    }

    /**
     * @depends      testLangDelete
     */
    public function testLangNotFound()
    {
        $response = $this->queryLangManage('GET', false);

        $langGet = $this->assertJsonResponse($response, 404, true);

        $this->assertObjectHasAttribute('message', $langGet);
        $this->assertObjectHasAttribute('code', $langGet);
        $this->assertStringEndsWith(self::LANG, $langGet->message);
    }

}