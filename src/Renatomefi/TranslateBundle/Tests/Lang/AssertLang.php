<?php

namespace Renatomefi\TranslateBundle\Tests\Lang;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssertLang
 * @package Renatomefi\TranslateBundle\Tests
 * @codeCoverageIgnore
 */
trait AssertLang
{
    /**
     * @inheritdoc
     */
    public function assertLangStructure($langObj)
    {
        $this->assertObjectHasAttribute('id', $langObj);
        $this->assertObjectHasAttribute('last_update', $langObj);
        $this->assertObjectHasAttribute('key', $langObj);
        $this->assertObjectHasAttribute('translations', $langObj);
    }

    /**
     * @inheritdoc
     */
    public function assertLangTranslationFormat($translation)
    {
        $this->assertObjectHasAttribute('id', $translation);
        $this->assertObjectHasAttribute('key', $translation);
        $this->assertObjectHasAttribute('value', $translation);
        $this->assertObjectHasAttribute('language', $translation);
    }

    /**
     * @inheritdoc
     */
    public function assertLangTranslationData($translation, $skipValue = false)
    {
        $this->assertEquals(static::TRANSLATION_KEY, $translation->key);
        $this->assertEquals(static::LANG, $translation->language->key);
        if (FALSE === $skipValue) $this->assertEquals(static::TRANSLATION_VALUE, $translation->value);

    }

    /**
     * @inheritdoc
     */
    public function assertLangTranslationNotFound(Response $response)
    {
        $notFound = $this->assertJsonResponse($response, 404, true);

        $baseFormat = 'No key "%s" found for lang "%s"';
        $this->assertStringMatchesFormat($baseFormat, $notFound->message);
        $this->assertSame(sprintf($baseFormat, static::TRANSLATION_KEY, static::LANG), $notFound->message);
    }
}