<?php

namespace Renatomefi\TranslateBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Renatomefi\TranslateBundle\Document\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $user = $this->get('security.context')->getToken()->getUser();
    }

    public function getLangsAction()
    {
        $dm = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
        $languages = $dm->findAll();

        if (!$languages) {
            throw $this->createNotFoundException('There are no languages');
        }

        $view = $this->view($languages);

        return $this->handleView($view);

    }

    public function getLangAction($lang)
    {
        $dm = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
        $language = $dm->findOneBy(array('key' => $lang));

        if (!$language) {
            throw $this->createNotFoundException('Language not found: ' . $lang);
        }

        $view = $this->view(['lang' => $language]);

        return $this->handleView($view);
    }
}
