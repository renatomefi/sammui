<?php

namespace Renatomefi\TranslateBundle\Controller;

use Renatomefi\TranslateBundle\Document\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LanguageController extends Controller
{
    /**
     * @Route("/{lang}")
     */
    public function getAction($lang)
    {

        $dm = $this->get('doctrine_mongodb')->getManager();

        $language = new Language();
        $language->setKey($lang);
        $language->setLastUpdate(time());

        $dm->persist($language);
        $dm->flush();


        return new JsonResponse(['persisted' => $language->getId()]);
//        return $this->render('TranslateBundle:Default:index.html.twig');
    }

}
