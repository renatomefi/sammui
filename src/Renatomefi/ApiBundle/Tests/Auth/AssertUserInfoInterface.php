<?php

namespace Renatomefi\ApiBundle\Tests\Auth;


interface AssertUserInfoInterface
{

    public function assertUserInfoObjStructure($userInfo);

    public function assertUserInfoObjNoAuth($userInfo);

    public function assertUserInfoObjAdminAuth($userInfo);

}