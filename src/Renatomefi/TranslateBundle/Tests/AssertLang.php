<?php

namespace Renatomefi\TranslateBundle\Tests;


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
    public function assertLangTranslationData($translation)
    {
        $this->assertEquals(static::TRANSLATION_KEY, $translation->key);
        $this->assertEquals(static::TRANSLATION_VALUE, $translation->value);
        $this->assertEquals(static::LANG, $translation->language->key);
    }
}