<?php

namespace Renatomefi\TranslateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LanguageController extends Controller
{
    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        var_dump($this->getUser()->getRoles());
        return $this->render('TranslateBundle:Default:admin.html.twig');
    }

}