<?php

namespace Renatomefi\ApiBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OAuth2\OAuth2;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOAuthClient implements FixtureInterface, ContainerAwareInterface
{

    const CLIENT_NAME = 'sammui-php-unit';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->createClient();
        $client->setName(static::CLIENT_NAME);
        $client->setRedirectUris(['/']);
        $client->setAllowedGrantTypes([
            OAuth2::GRANT_TYPE_AUTH_CODE,
            OAuth2::GRANT_TYPE_USER_CREDENTIALS,
            OAuth2::GRANT_TYPE_REFRESH_TOKEN,
            OAuth2::GRANT_TYPE_IMPLICIT,
            OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS
        ]);

        $clientManager->updateClient($client);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}