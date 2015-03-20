<?php

namespace Renatomefi\TranslateBundle\Tests\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface AssertLangInterface
 * @package Renatomefi\TranslateBundle\Tests
 * @codeCoverageIgnore
 */
interface AssertLangInterface
{
    /**
     * Assert Language Structure from Language API
     * @param $langObj
     */
    public function assertLangStructure($langObj);

    /**
     * Assert Translation Structure from Translate API
     * @param $translation
     */
    public function assertLangTranslationFormat($translation);

    /**
     * Assert Translation Data from Translate API
     * @param $translation
     */
    public function assertLangTranslationData($translation);

    /**
     * Assert a typical Lang Translation not found
     * @param Response $response
     * @return mixed
     */
    public function assertLangTranslationNotFound(Response $response);
}