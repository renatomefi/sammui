<?php

namespace Renatomefi\TranslateBundle\Tests\Lang;

use Renatomefi\TranslateBundle\Document\Translation;
use Renatomefi\TranslateBundle\Document\Language;

/**
 * Class AssertLang
 * @package Renatomefi\TranslateBundle\Tests
 * @codeCoverageIgnore
 */
interface AssertLangDocumentInterface
{
    /**
     * @param Language $lang
     * @return mixed
     */
    public function assertLangDocument(Language $lang);

    /**
     * @param Language $lang
     * @return mixed
     */
    public function assertLangDocumentData(Language $lang);

    /**
     * @param Translation $translation
     * @return mixed
     */
    public function assertLangTranslationDocument(Translation $translation);

    /**
     * @param Translation $translation
     * @return mixed
     */
    public function assertLangTranslationDocumentData(Translation $translation);
}