<?php

namespace Renatomefi\TranslateBundle\Tests;

trait Lang
{
    protected function assertLangStructure($langObj)
    {
        $this->assertObjectHasAttribute('id', $langObj);
        $this->assertObjectHasAttribute('last_update', $langObj);
        $this->assertObjectHasAttribute('key', $langObj);
        $this->assertObjectHasAttribute('translations', $langObj);
    }
}