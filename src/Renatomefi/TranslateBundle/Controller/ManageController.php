<?php

namespace Renatomefi\TranslateBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;
use Symfony\Component\HttpFoundation\Request;

class ManageController extends FOSRestController
{

    public function postLangAction($lang)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $language = new Language();
        $language->setKey($lang);
        $language->setLastUpdate(time());

        $dm->persist($language);
        $dm->flush();

        $view = $this->view($language);

        return $this->handleView($view);
    }

    public function postLangKeyAction(Request $request, $lang, $key)
    {
        $languageDM = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
        $language = $languageDM->findOneBy(array('key' => $lang));

        if (!$language) {
            throw $this->createNotFoundException('Language not found: ' . $lang);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();

        $translation = new Translation();
        $translation->setLanguage($language);
        $translation->setKey($key);
        $translation->setValue($request->get('value'));

        $dm->persist($translation);
        $dm->flush();

        $view = $this->view($translation);

        return $this->handleView($view);

    }

    public function getLangKeysAction($lang)
    {
        $languageDM = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
        $language = $languageDM->findOneByKey($lang);

        if (!$language) {
            throw $this->createNotFoundException('Language not found: ' . $lang);
        }

            $translations = $language->getTranslations();

        $view = $this->view($translations);

        return $this->handleView($view);
    }

    public function getLangKeyAction($lang, $key)
    {
        $view = $this->view(['lang' => $lang, 'key' => $key], 200);

        return $this->handleView($view);

    }

    public function deleteLangKeyAction($lang, $key)
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

        $view = $this->view($language);

        return $this->handleView($view);
    }
}
