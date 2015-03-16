<?php

namespace Renatomefi\TranslateBundle\Tests\Controller;

use Renatomefi\ApiBundle\Tests\Auth\OAuthClient;
use Renatomefi\ApiBundle\Tests\Auth\OAuthClientInterface;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtils;
use Renatomefi\TestBundle\MongoDB\AssertMongoUtilsInterface;
use Renatomefi\TestBundle\Rest\AssertRestUtils;
use Renatomefi\TestBundle\Rest\AssertRestUtilsInterface;
use Renatomefi\TranslateBundle\Tests\AssertLang;
use Renatomefi\TranslateBundle\Tests\AssertLangInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManageControllerTest
 * @package Renatomefi\TranslateBundle\Tests\Controller
 */
class ManageControllerTest extends WebTestCase implements AssertRestUtilsInterface, AssertMongoUtilsInterface, AssertLangInterface, OAuthClientInterface
{

    use AssertMongoUtils, AssertRestUtils, AssertLang, OAuthClient;

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
     * @param string $authRole
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    protected function queryLangManage($method = 'GET', $assertJson = true, $authRole = null)
    {
        $client = static::createClient();

        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json'
        ];

        if ($authRole) {
            $requestHeaders['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->getCredentialsByRole($authRole)->access_token;
        }

        $client->request($method, '/l10n/manage/langs/' . static::LANG, [], [], $requestHeaders);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, Response::HTTP_OK, true) : $response;
    }

    /**
     * @param string $method
     * @param bool $params
     * @param bool $noKey
     * @param bool $assertJson
     * @param string $authRole
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    protected function queryLangTranslationManage($method = 'GET', $params = false, $noKey = false, $assertJson = true, $authRole = null)
    {
        $requestParams = [];

        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json'
        ];

        if ($authRole) {
            $requestHeaders['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->getCredentialsByRole($authRole)->access_token;
        }

        $keyString = (TRUE === $noKey) ? '/keys' : '/keys/' . static::TRANSLATION_KEY;

        if (TRUE === $params) {
            $requestParams['value'] = static::TRANSLATION_VALUE;
        } elseif (is_array($params)) {
            $requestParams = $params;
        } elseif (is_string($params)) {
            $requestParams['value'] = $params;
        }

        $client = static::createClient();

        $client->request($method, '/l10n/manage/langs/' . static::LANG . $keyString, $requestParams, [], $requestHeaders);

        $response = $client->getResponse();

        return (TRUE === $assertJson) ? $this->assertJsonResponse($response, Response::HTTP_OK, true) : $response;
    }

    /**
     * @return array
     */
    public function getLangHTTPMethods()
    {
        return [
            [Request::METHOD_POST, Response::HTTP_UNAUTHORIZED],
            [Request::METHOD_PUT, Response::HTTP_METHOD_NOT_ALLOWED],
            [Request::METHOD_PATCH, Response::HTTP_METHOD_NOT_ALLOWED],
            [Request::METHOD_DELETE, Response::HTTP_UNAUTHORIZED],
            [Request::METHOD_GET, [Response::HTTP_NOT_FOUND, Response::HTTP_OK]]
        ];
    }

    /**
     * @return array
     */
    public function getLangTranslationHTTPMethods()
    {
        return [
            [Request::METHOD_POST, Response::HTTP_UNAUTHORIZED],
            [Request::METHOD_PUT, Response::HTTP_UNAUTHORIZED],
            [Request::METHOD_PATCH, Response::HTTP_METHOD_NOT_ALLOWED],
            [Request::METHOD_DELETE, Response::HTTP_UNAUTHORIZED],
            [Request::METHOD_GET, [Response::HTTP_NOT_FOUND, Response::HTTP_OK]]
        ];
    }

    /**
     * Test Lang Firewall
     * @dataProvider getLangHTTPMethods
     * @param string $method HTTP Method to test
     * @param mixed $statusCode Expected HTTP Status Code resulted from test
     */
    public function testLangFirewall($method, $statusCode)
    {
        $response = $this->queryLangManage($method, false);

        $this->assertJsonResponse($response, $statusCode);
    }

    /**
     * Test Lang Translation Firewall
     * @dataProvider getLangTranslationHTTPMethods
     * @param string $method HTTP Method to test
     * @param mixed $statusCode Expected HTTP Status Code resulted from test
     */
    public function testLangTranslationFirewall($method, $statusCode)
    {
        $response = $this->queryLangTranslationManage($method, false, false, false);

        $this->assertJsonResponse($response, $statusCode);
    }

    /**
     * Test creating a new Lang
     */
    public function testLangCreate()
    {
        $lang = $this->queryLangManage('POST', true, 'ROLE_ADMIN');

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
        $translation = $this->queryLangTranslationManage('POST', true, false, true, 'ROLE_ADMIN');

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation);
    }

    /**
     * Test Translation duplication for Lang
     * @depends      testLangTranslationCreate
     */
    public function testLangTranslationCreateDuplicate()
    {
        $response = $this->queryLangTranslationManage('POST', true, false, false, 'ROLE_ADMIN');

        $translation = $this->assertJsonResponse($response, Response::HTTP_CONFLICT, true);

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
     * Test Lang list in array format
     * @depends      testLangTranslationCreate
     */
    public function testLangKeys()
    {
        $translations = $this->queryLangTranslationManage('GET', false, true, false);

        $ts = json_decode($translations->getContent());

        $foundTranslation = false;
        foreach ($ts as $t) {
            if ($t->key == static::TRANSLATION_KEY) $foundTranslation = $t;
        }
        $this->assertNotEmpty($foundTranslation, 'Didn\'t find the translation on list');

        $this->assertLangTranslationFormat($foundTranslation);
        $this->assertLangTranslationData($foundTranslation);
    }

    /**
     * Test Editing the Translation
     * @depends      testLangKeys
     * @depends      testLangTranslationGet
     */
    public function testLangTranslationEdit()
    {
        $translation = $this->queryLangTranslationManage('PUT', self::TRANSLATION_VALUE . '-edited', false, true, 'ROLE_ADMIN');

        $this->assertLangTranslationFormat($translation);
        $this->assertLangTranslationData($translation, true);
        $this->assertNotEquals(static::TRANSLATION_VALUE, $translation->value);
        $this->assertEquals(static::TRANSLATION_VALUE . '-edited', $translation->value);
    }

    /**
     * Test deleting the Translation
     * @depends      testLangTranslationEdit
     * @depends      testLangTranslationCreateDuplicate
     */
    public function testLangTranslationDelete()
    {
        $translationDelete = $this->queryLangTranslationManage('DELETE', false, false, true, 'ROLE_ADMIN');

        $this->assertMongoDeleteFormat($translationDelete, true);
    }

    /**
     * Test Getting the Translation that does not exists
     * @depends      testLangTranslationDelete
     */
    public function testLangTranslationNotFound()
    {
        $response = $this->queryLangTranslationManage('GET', false, false, false);

        $this->assertLangTranslationNotFound($response);
    }

    /**
     * Test Editing the Translation that does not exists
     * @depends      testLangTranslationDelete
     */
    public function testLangTranslationEditNotFound()
    {
        $response = $this->queryLangTranslationManage('PUT', self::TRANSLATION_VALUE . '-edited', false, false, 'ROLE_ADMIN');

        $this->assertLangTranslationNotFound($response);
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
        $response = $this->queryLangManage('POST', false, 'ROLE_ADMIN');

        $duplicate = $this->assertJsonResponse($response, Response::HTTP_CONFLICT, true);

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

        $langs = $this->assertJsonResponse($response, Response::HTTP_OK, true, true);

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

        $langs = $this->assertJsonResponse($response, Response::HTTP_OK, true);

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
        $langDelete = $this->queryLangManage('DELETE', true, 'ROLE_ADMIN');

        $this->assertMongoDeleteFormat($langDelete, true);
    }

    /**
     * Test retrieving a non existent Lang
     * @depends      testLangDelete
     */
    public function testLangNotFound()
    {
        $response = $this->queryLangManage('GET', false);

        $langGet = $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND, true);

        $this->assertObjectHasAttribute('message', $langGet);
        $this->assertObjectHasAttribute('code', $langGet);
        $this->assertStringEndsWith(self::LANG, $langGet->message);
    }

}