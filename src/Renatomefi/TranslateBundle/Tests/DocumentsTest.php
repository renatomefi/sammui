<?php

namespace Renatomefi\TranslateBundle\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use Renatomefi\TestBundle\MongoDB\AssertMongoId;
use Renatomefi\TestBundle\MongoDB\AssertMongoIdInterface;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;
use Renatomefi\TranslateBundle\Tests\Lang\AssertLangDocument;
use Renatomefi\TranslateBundle\Tests\Lang\AssertLangDocumentInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class DocumentsTest
 * @package Renatomefi\TranslateBundle\Tests
 */
class DocumentsTest extends KernelTestCase implements AssertMongoIdInterface, AssertLangDocumentInterface
{

    use AssertLangDocument, AssertMongoId;

    /**
     * Default Language name
     */
    const LANG = 'unit-test-document';

    /**
     * Default translate key
     */
    const TRANSLATION_KEY = 'unit-test-document-translation-key';

    /**
     * Default translate value
     */
    const TRANSLATION_VALUE = 'unit-test-document-translation-value';

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->documentManager = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
    }

    /**
     * @return object
     */
    public function testLang()
    {
        $language = new Language();
        $language->setKey(static::LANG);
        $language->setLastUpdate(new \MongoDate());

        $translation = new Translation();
        $translation->setKey(static::TRANSLATION_KEY);
        $translation->setValue(static::TRANSLATION_VALUE);
        $translation->setLanguage($language);
        $language->addTranslation($translation);

        $this->documentManager->persist($language);
        $this->documentManager->persist($translation);

        $this->documentManager->flush();

        $this->assertLangDocument($language);
        $this->assertLangDocumentData($language);
        $this->assertLangTranslationDocument($translation);
        $this->assertLangTranslationDocumentData($translation);
        $this->assertLangTranslationDocumentData($language->getTranslations()->getIterator()[0]);

        return (object)[
            'language' => $language,
            'translation' => $translation
        ];

    }

    /**
     * @depends testLang
     * @param $lang
     */
    public function testTranslationDelete($lang)
    {
        $language = $this->documentManager->find(get_class($lang->language), $lang->language->getId());
        $translation = $this->documentManager->find(get_class($lang->translation), $lang->translation->getId());

        $language->removeTranslation($translation);

        $this->documentManager->flush();

        $this->documentManager->remove($translation);
        $this->documentManager->remove($language);

        $this->documentManager->flush();
    }
}