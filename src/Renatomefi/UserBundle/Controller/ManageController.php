<?php

namespace Renatomefi\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class ManageController
 * @package Renatomefi\UserBundle\Controller
 */
class ManageController extends FOSRestController
{

    public function getUsersInfoAction()
    {
        $user = $this->get('doctrine_mongodb')->getRepository('UserBundle:User');

        return $user->findAll();
    }

    public function getUserAction($username)
    {
        $user = $this->get('doctrine_mongodb')->getRepository('UserBundle:User');

        $result = $user->findOneByUsername($username);

        if (!$result) {
            throw $this->createNotFoundException("No user found with username '$username'");
        }

        return $result;
    }

}
