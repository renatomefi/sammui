<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUsers
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadUsers implements FixtureInterface, OrderedFixtureInterface    , ContainerAwareInterface
{

    /**
     * Default username
     */
    const USER_USERNAME = 'sammui';
    /**
     * Default password
     */
    const USER_PASSWORD = 'sammui';

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
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $user->setUsername(static::USER_USERNAME);
        $user->setPlainPassword(static::USER_PASSWORD);
        $user->setEmail('sammui-project@renatomefi.com.br');
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $user->addRole('ROLE_ADMIN');

        $userManager->updateUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}