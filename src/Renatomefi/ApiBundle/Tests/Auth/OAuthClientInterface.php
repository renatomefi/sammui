<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use Renatomefi\ApiBundle\Document\Client;

/**
 * Interface OAuthClientInterface
 * @package Renatomefi\ApiBundle\Tests\Auth
 * @codeCoverageIgnore
 */
interface OAuthClientInterface
{
    /**
     * @return Client
     */
    public function getOAuthClient();

    /**
     * @param array $params
     * @return mixed
     */
    public function queryOAuth2Token($params = []);

    /**
     * @return mixed
     */
    public function getAnonymousCredentials();

    /**
     * @return mixed
     */
    public function getAdminCredentials();
}