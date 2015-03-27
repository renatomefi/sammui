<?php

namespace Renatomefi\UserBundle\Tests;

use FOS\UserBundle\Document\UserManager;
use Renatomefi\UserBundle\DataFixtures\MongoDB\LoadUsers;
use Renatomefi\UserBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserTest
 * @package Renatomefi\UserBundle\Tests
 */
class UserTest extends KernelTestCase
{
    /**
     * @var UserManager
     */
    protected $userManager;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->userManager = $kernel->getContainer()->get('fos_user.user_manager');
    }

    /**
     * @return User
     */
    public function testUserNew()
    {
        $user = $this->userManager->createUser();

        $user->setName('Name ' . LoadUsers::USER_USERNAME . '-phpunit');
        $user->setUsername(LoadUsers::USER_USERNAME . '-phpunit');
        $user->setPlainPassword(LoadUsers::USER_PASSWORD);
        $user->setEmail('sammui-project-phpunit@renatomefi.com.br');
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $user->addRole('ROLE_USER');

        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * @depends testUserNew
     * @param User $user
     */
    public function testUserData(User $user)
    {
        $this->assertNotNull($user->getId());
        $this->assertNotNull($user->getCreatedAt());
        $this->assertNotNull($user->getName());
        $this->assertNotNull($user->getEmailCanonical());
        $this->assertNotNull($user->getRoles());
        $this->assertNotNull($user->getUsernameCanonical());
    }

    /**
     * @depends testUserNew
     * @depends testUserData
     * @param User $user
     */
    public function testUserDelete(User $user)
    {
        $user = $this->userManager->findUserBy(['username' => $user->getUsername()]);
        $this->userManager->deleteUser($user);
    }
}
