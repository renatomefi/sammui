<?php

namespace Renatomefi\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FormBundle:Default:index.html.twig', array('name' => $name));
    }
}
