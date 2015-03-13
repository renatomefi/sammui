<?php

namespace Renatomefi\ApiBundle\Tests\Auth;

/**
 * Interface AssertUserInfoInterface
 * @package Renatomefi\ApiBundle\Tests\Auth
 * @codeCoverageIgnore
 */
interface AssertUserInfoInterface
{

    /**
     * @param $userInfo
     * @return mixed
     */
    public function assertUserInfoObjStructure($userInfo);

    /**
     * @param $userInfo
     * @return mixed
     */
    public function assertUserInfoObjNoAuth($userInfo);

    /**
     * @param $userInfo
     * @return mixed
     */
    public function assertUserInfoObjAdminAuth($userInfo);

}