<?php

namespace Renatomefi\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use FOS\OAuthServerBundle\Document\AccessToken as BaseAccessToken;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AccessToken
 * @package Renatomefi\ApiBundle\Document
 */
class Token extends BaseAccessToken
{
    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\ReferenceOne(targetDocument="Client")
     */
    protected $client;

    /**
     * @ODM\ReferenceOne(targetDocument="Renatomefi\UserBundle\Document\User")
     */
    protected $user;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client
     *
     * @param \Renatomefi\ApiBundle\Document\Client $client
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return \Renatomefi\ApiBundle\Document\Client $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param \Renatomefi\UserBundle\Document\User $user
     * @return self
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Renatomefi\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
