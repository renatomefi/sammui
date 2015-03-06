<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\Test\MongoDB\Utils;
use Renatomefi\Test\RestTestCase;
use Renatomefi\TranslateBundle\Tests\Lang;

class ManageControllerTest extends RestTestCase
{

    use Utils, Lang;

    const LANG = 'unit-test';
    const TRANSLATION_KEY = 'unit-test-translation-key';
    const TRANSLATION_VALUE = 'unit-test-translation-value';

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
    public function testLangTranslationCreate()
    {
        $client = static::createClient();

        $client->request('POST', '/l10n/manage/langs/' . static::LANG . '/keys/' . static::TRANSLATION_KEY,
            [
                'value' => static::TRANSLATION_VALUE
            ], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();
        $translation = $this->assertJsonResponse($response, 200, true);

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationCreateDuplicate()
    {
        $client = static::createClient();

        $client->request('POST', '/l10n/manage/langs/' . static::LANG . '/keys/' . static::TRANSLATION_KEY,
            [
                'value' => static::TRANSLATION_VALUE
            ], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();
        $translation = $this->assertJsonResponse($response, 409, true);
        $this->assertMongoDuplicateEntry($translation, self::TRANSLATION_KEY);
    }

    /**
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationGet()
    {
        $client = static::createClient();

        $client->request('GET', '/l10n/manage/langs/' . static::LANG . '/keys/' . static::TRANSLATION_KEY,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $translation = $this->assertJsonResponse($client->getResponse(), 200, true);

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * @depends      testLangTranslationGet
     * @depends      testLangTranslationCreateDuplicate
     */
    public function testLangTranslationDelete()
    {
        $client = static::createClient();

        $client->request('DELETE', '/l10n/manage/langs/' . static::LANG . '/keys/' . static::TRANSLATION_KEY,
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $translationDelete = $this->assertJsonResponse($response, 200, true);

        $this->assertMongoDeleteFormat($translationDelete, true);
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
     * @depends      testLangCreate
     */
    public function testLangDuplicate()
    {
        $response = $this->queryLangManage('POST', false);

        $duplicate = $this->assertJsonResponse($response, 409, true);

        $this->assertMongoDuplicateEntry($duplicate, self::LANG);
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
     * @depends      testLangTranslationDelete
     */
    public function testLangDelete()
    {
        $langDelete = $this->queryLangManage('DELETE');

        $this->assertMongoDeleteFormat($langDelete, true);
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