<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtilsInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Renatomefi\TranslateBundle\Tests\AssertLang;
use Renatomefi\TranslateBundle\Tests\AssertLangInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ManageControllerTest
 * @package Renatomefi\TranslateBundle\Tests\Controller
 */
class ManageControllerTest extends WebTestCase implements AssertRestUtilsInterface, AssertMongoUtilsInterface, AssertLangInterface
{

    use AssertMongoUtils, AssertRestUtils, AssertLang;

    /**
     * Default Language name
     */
    const LANG = 'unit-test';

    /**
     * Default translate key
     */
    const TRANSLATION_KEY = 'unit-test-translation-key';

    /**
     * Default translate value
     */
    const TRANSLATION_VALUE = 'unit-test-translation-value';

    /**
     * @param string $method
     * @param bool $assertJson
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    protected function queryLangManage($method = 'GET', $assertJson = true)
    {
        $client = static::createClient();

        $client->request($method, '/l10n/manage/langs/' . static::LANG, [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, 200, true) : $response;
    }

    /**
     * @param string $method
     * @param bool $setValue
     * @param bool $assertJson
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Test creating a new Lang
     */
    public function testLangCreate()
    {
        $lang = $this->queryLangManage('POST');

        $this->assertLangStructure($lang);
        $this->assertEquals(static::LANG, $lang->key);
        $this->assertMongoDateFormat($lang->last_update);
    }

    /**
     * Test creating a new Translation for Lang
     * @depends      testLangCreate
     */
    public function testLangTranslationCreate()
    {
        $translation = $this->queryLangTranslationManage('POST', true);

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * Test Translation duplication for Lang
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationCreateDuplicate()
    {
        $response = $this->queryLangTranslationManage('POST', true, false);

        $translation = $this->assertJsonResponse($response, 409, true);

        $this->assertMongoDuplicateEntry($translation, self::TRANSLATION_KEY);
    }

    /**
     * Test Getting the Translation
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationGet()
    {
        $translation = $this->queryLangTranslationManage();

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * Test deleting the Translation
     * @depends      testLangTranslationGet
     * @depends      testLangTranslationCreateDuplicate
     */
    public function testLangTranslationDelete()
    {
        $translationDelete = $this->queryLangTranslationManage('DELETE');

        $this->assertMongoDeleteFormat($translationDelete, true);
    }

    /**
     * Testing getting the Lang
     * @depends      testLangCreate
     */
    public function testLangGet()
    {
        $langGet = $this->queryLangManage();

        $this->assertLangStructure($langGet);
        $this->assertEquals(self::LANG, $langGet->key);
    }

    /**
     * Test duplicating the Lang
     * @depends      testLangCreate
     */
    public function testLangDuplicate()
    {
        $response = $this->queryLangManage('POST', false);

        $duplicate = $this->assertJsonResponse($response, 409, true);

        $this->assertMongoDuplicateEntry($duplicate, self::LANG);
    }

    /**
     * Test the entire Lang list
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
     * Test all Langs info
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
     * Test delete the Lang
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
     * Test retrieving a non existent Lang
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