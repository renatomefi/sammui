<?php

namespace Renatomefi\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use FOS\OAuthServerBundle\Document\Client as BaseClient;


/**
 * @ODM\Document
 */
class Client extends BaseClient
{

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }
}
