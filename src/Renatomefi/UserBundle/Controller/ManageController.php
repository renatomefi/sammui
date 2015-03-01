<?php

namespace Renatomefi\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;



class ManageController extends FOSRestController
{

    public function getUsersInfoAction()
    {
        $user = $this->get('doctrine_mongodb')->getRepository('UserBundle:User');

        $result = $user->findAll();

        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function getUserAction($username)
    {
        $user = $this->get('doctrine_mongodb')->getRepository('UserBundle:User');

        $result = $user->findOneByUsername($username);

        $view = $this->view($result);
        return $this->handleView($view);
    }

}
