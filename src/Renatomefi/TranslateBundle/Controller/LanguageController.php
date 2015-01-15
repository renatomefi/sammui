<?php

namespace Renatomefi\TranslateBundle\Controller;

use Renatomefi\TranslateBundle\Document\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LanguageController extends Controller
{
    public function getAction($lang)
    {



        return new JsonResponse(['persisted' => $lang]);
//        return $this->render('TranslateBundle:Default:index.html.twig');
    }

}
