<?php

namespace PensandoODireito\SisdepenFormsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SisdepenFormsBundle:Default:index.html.twig', array('name' => $name));
    }
}
