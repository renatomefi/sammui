<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TranslateBundle\Tests\Lang;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManageControllerTest extends WebTestCase
{

    use AssertMongoUtils, AssertRestUtils, Lang;

    const LANG = 'unit-test';
    const TRANSLATION_KEY = 'unit-test-translation-key';
    const TRANSLATION_VALUE = 'unit-test-translation-value';

    protected function queryLangManage($method = 'GET', $assertJson = true)
    {
        $client = static::createClient();

        $client->request($method, '/l10n/manage/langs/' . static::LANG, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, 200, true) : $response;
    }

    protected function queryLangTranslationManage($method = 'GET', $setValue = false, $assertJson = true)
    {
        $params = [];

        if (TRUE === $setValue) {
            $params['value'] = static::TRANSLATION_VALUE;
        } elseif (is_array($setValue)) {
            $params = $setValue;
        }

        $client = static::createClient();

        $client->request($method, '/l10n/manage/langs/' . static::LANG . '/keys/' . static::TRANSLATION_KEY, $params, [], [
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
        $translation = $this->queryLangTranslationManage('POST', true);

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationCreateDuplicate()
    {
        $response = $this->queryLangTranslationManage('POST', true, false);

        $translation = $this->assertJsonResponse($response, 409, true);

        $this->assertMongoDuplicateEntry($translation, self::TRANSLATION_KEY);
    }

    /**
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationGet()
    {
        $translation = $this->queryLangTranslationManage();

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * @depends      testLangTranslationGet
     * @depends      testLangTranslationCreateDuplicate
     */
    public function testLangTranslationDelete()
    {
        $translationDelete = $this->queryLangTranslationManage('DELETE');

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
     * @depends testLangCreate
     */
    public function testLangsInfo()
    {
        $client = static::createClient();

        $client->request('GET', '/l10n/manage/langs/info',
            [], [], [
                'HTTP_ACCEPT' => 'application/json'
            ]);

        $response = $client->getResponse();

        $langs = $this->assertJsonResponse($response, 200, true);

        $foundLang = false;
        foreach ($langs as $lang) {
            if ($lang->key == static::LANG) $foundLang = true;
        }
        $this->assertTrue($foundLang, 'Didn\'t find the lang on langs list');
    }

    /**
     * @depends      testLangGet
     * @depends      testLangsList
     * @depends      testLangsInfo
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