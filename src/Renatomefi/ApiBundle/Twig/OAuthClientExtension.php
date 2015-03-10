<?php

namespace Renatomefi\ApiBundle\Twig;


use FOS\OAuthServerBundle\Document\ClientManager;
use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;

class OAuthClientExtension extends \Twig_Extension
{

    protected $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'oauth_client_extension';
    }

    /**
     * @inheritdoc
     */
    public function getGlobals()
    {

        $client = $this->clientManager->findClientBy(['name' => LoadOAuthClient::APP_CLIENT_NAME]);

        if (!$client) {
            $err = 'no-client-found-for-' . LoadOAuthClient::APP_CLIENT_NAME;
            return [
                'OAuthClientId' => $err,
                'OAuthClientSecret' => $err
            ];
        }

        return [
            'OAuthClientId' => $client->getPublicId(),
            'OAuthClientSecret' => $client->getSecret()
        ];
    }
}