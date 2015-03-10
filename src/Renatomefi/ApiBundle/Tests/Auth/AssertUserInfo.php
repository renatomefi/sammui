<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

trait AssertUserInfo
{
    protected function assertUserInfoObjStructure($userInfo)
    {
        $this->assertObjectHasAttribute('authenticated', $userInfo);
        $this->assertObjectHasAttribute('authenticated_fully', $userInfo);
        $this->assertObjectHasAttribute('authenticated_anonymously', $userInfo);
        $this->assertObjectHasAttribute('role_user', $userInfo);
        $this->assertObjectHasAttribute('role_admin', $userInfo);
        $this->assertObjectHasAttribute('role_anonymous', $userInfo);
        $this->assertObjectHasAttribute('client', $userInfo);
        $this->assertObjectHasAttribute('user', $userInfo);
    }

    protected function assertUserInfoObjNoAuth($userInfo)
    {
        $this->assertTrue($userInfo->authenticated);
        $this->assertFalse($userInfo->authenticated_fully);
        $this->assertTrue($userInfo->authenticated_anonymously);
        $this->assertTrue($userInfo->role_anonymous);
        $this->assertFalse($userInfo->role_user);
        $this->assertFalse($userInfo->role_admin);
        $this->assertEmpty($userInfo->user);
        $this->assertEmpty($userInfo->client);
    }

    protected function assertUserInfoObjAdminAuth($userInfo)
    {
        $this->assertTrue($userInfo->authenticated);
        $this->assertTrue($userInfo->authenticated_fully);
        $this->assertTrue($userInfo->authenticated_anonymously);
        $this->assertTrue($userInfo->role_anonymous);
        $this->assertTrue($userInfo->role_user);
        $this->assertTrue($userInfo->role_admin);
        $this->assertNotEmpty($userInfo->user);
        $this->assertNotEmpty($userInfo->client);
    }
}