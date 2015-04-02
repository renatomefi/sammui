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

namespace Renatomefi\FormBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class ProtocolFile extends File
{
    /**
     * @ODM\String
     */
    protected $title;

    /**
     * @ODM\String
     */
    protected $description;

    /**
     * @ODM\ReferenceOne(targetDocument="Protocol")
     */
    protected $protocol;

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set protocol
     *
     * @param \Renatomefi\FormBundle\Document\Protocol $protocol
     * @return self
     */
    public function setProtocol(Protocol $protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * Get protocol
     *
     * @return \Renatomefi\FormBundle\Document\Protocol $protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
