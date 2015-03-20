<?php

namespace Renatomefi\TranslateBundle\Tests\Lang;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;

/**
 * Class AssertLang
 * @package Renatomefi\TranslateBundle\Tests
 * @codeCoverageIgnore
 */
trait AssertLangDocument
{

    /**
     * @inheritdoc
     */
    public function assertLangDocument(Language $lang)
    {
        $this->assertNotNull($lang->getId());
        $this->assertNotNull($lang->getKey());
        $this->assertNotNull($lang->getLastUpdate());
        $this->assertNotNull($lang->getTranslations());
    }

    /**
     * @inheritdoc
     */
    public function assertLangDocumentData(Language $lang)
    {
        $this->assertMongoId($lang->getId());
        $this->assertTrue((strlen($lang->getKey()) > 0));
        $this->assertTrue((count($lang->getTranslations()) > 0));
        $this->assertTrue(($lang->getLastUpdate() instanceof \MongoDate));
    }

    /**
     * @inheritdoc
     */
    public function assertLangTranslationDocument(Translation $translation)
    {
        $this->assertObjectHasAttribute('id', $translation);
        $this->assertObjectHasAttribute('key', $translation);
        $this->assertObjectHasAttribute('value', $translation);
        $this->assertObjectHasAttribute('language', $translation);
    }

    /**
     * @inheritdoc
     */
    public function assertLangTranslationDocumentData(Translation $translation)
    {
        $this->assertMongoId($translation->getId());
        $this->assertTrue((strlen($translation->getKey()) > 0));
        $this->assertTrue((strlen($translation->getValue()) > 0));
        $this->assertTrue(($translation->getLanguage() instanceof Language));
    }
}