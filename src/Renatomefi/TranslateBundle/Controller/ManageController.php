<?php

namespace Renatomefi\TranslateBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ManageController extends FOSRestController
{
    public function postKeyAction($lang, $key, $value)
    {

    }

    public function getKeysAction($lang)
    {
        $view = $this->view(['lang' => $lang, 'keys' => ['a', 'b', 'c']], 200);

        return $this->handleView($view);
    }

    /**
     * @Get("/{lang}/key/{key}")
     */
    public function getKeyAction($lang, $key)
    {
        $view = $this->view(['lang' => $lang, 'key' => $key], 200);

        return $this->handleView($view);

    }

    public function deleteKeyAction($lang, $key)
    {

    }

    public function getLangsAction()
    {
        $view = $this->view(['langs' => ['en-us', 'pt-br']], 200);

        return $this->handleView($view);

    }

    public function getLangAction($lang)
    {

    }
}
