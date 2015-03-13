<?php
/**
 * Created by PhpStorm.
 * User: renatomefi
 * Date: 13/03/15
 * Time: 16:31
 */
namespace Renatomefi\TranslateBundle\Tests;


/**
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
}