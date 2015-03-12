<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

use Renatomefi\ApiBundle\Document\Client;

/**
 * @codeCoverageIgnore
 */
interface OAuthClientInterface
{
    /**
     * @return Client
     */
    public function getOAuthClient();

    public function queryOAuth2Token($params = []);

    public function getAnonymousCredentials();

    public function getAdminCredentials();
}