<?php

/*
 * This file is part of sammui project.
 *
 * For the full copyright and license information, please
 * view the LICENSE file that was distributed with this
 * source code.
 *
 * Este arquivo faz parte do projeto sammui.
 *
 * Para acesso completo à licença e copyright, acesse o
 * arquivo LICENSE na raiz do projeto.
 *
 * (c) PensandooDireito SAL/MJ <https://github.com/pensandoodireito>
 * (c) Renato Mendes Figueiredo <renato@renatomefi.com.br>
 */

namespace Renatomefi\DependencyInjection\Tests;

use Flyers\FrontendBundle\DependencyInjection\FrontendExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Renatomefi\ApiBundle\DependencyInjection\ApiExtension;
use Renatomefi\DependencyInjection\Configuration;
use Renatomefi\DependencyInjection\NoExtension;
use Renatomefi\FormBundle\DependencyInjection\FormExtension;
use Renatomefi\TranslateBundle\DependencyInjection\TranslateExtension;
use Renatomefi\UserBundle\DependencyInjection\UserExtension;

/**
 * Class ExtensionTest
 * @package Renatomefi\DependencyInjection\Tests
 */
class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     * @return array
     */
    protected function getContainerExtensions()
    {
        return array(
            new ApiExtension(),
            new FormExtension(),
            new TranslateExtension(),
            new UserExtension(),
            new FrontendExtension()
        );
    }

    /**
     * If no Error means the test is Ok!
     */
    public function testLoadExtensions()
    {
        $this->load();
    }
}