<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Renatomefi\UserBundle\Document\User;

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
        $user = new User();
        $user->setUsername('sammui');
        $user->setUsernameCanonical('sammui');
        $user->setEmail('sammui-project@renatomefi.com.br');
        $user->setEmailCanonical('sammui-project@renatomefi.com.br');
        $user->setSalt(md5(uniqid()));
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->addRole(UserInterface::ROLE_SUPER_ADMIN);
        $user->addRole('ROLE_ADMIN');

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);
        $user->setPassword($encoder->encodePassword('sammui', $user->getSalt()));

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}