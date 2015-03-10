<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsers implements FixtureInterface, ContainerAwareInterface
{

    const USER_USERNAME = 'sammui';
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
        $um = $this->container->get('fos_user.user_manager');

        $user = $um->createUser();

        $user->setUsername(static::USER_USERNAME);
        $user->setPlainPassword(static::USER_PASSWORD);
        $user->setEmail('sammui-project@renatomefi.com.br');
        $user->setEnabled(true);
        $user->setSuperAdmin(true);
        $user->addRole('ROLE_ADMIN');

        $um->updateUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}