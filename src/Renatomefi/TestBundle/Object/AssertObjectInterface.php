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
namespace Renatomefi\TestBundle\Object;


/**
 * Class AssertObject
 * @package Renatomefi\TestBundle\Object
 */
interface AssertObjectInterface
{
    /**
     * @param array $attributes
     * @param $object
     */
    public function assertObjectHasAttributes(array $attributes, $object);

    /**
     * @param array $attributes
     * @param $object
     */
    public function assertObjectNotHasAttributes(array $attributes, $object);
}