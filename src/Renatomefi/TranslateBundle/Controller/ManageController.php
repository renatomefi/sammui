<?php

namespace Renatomefi\TranslateBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;
use Symfony\Component\HttpFoundation\Request;

class ManageController extends FOSRestController
{

    /**
     * Find and retrieve a language from Language repository
     *
     * @param $lang
     * @param bool $notFoundException Throw an exception if language is not found
     * @return mixed
     */
    public function getLang($lang, $notFoundException = false)
    {
        $languageDM = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
        $language = $languageDM->findOneByKey($lang);

        if (TRUE == $notFoundException && !$language) {
            throw $this->createNotFoundException('Language not found: ' . $lang);
        }

        return $language;
    }

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
        $language = $this->getLang($lang, true);

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

        $language = $this->getLang($lang, true);

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
        $this->getLang($lang, true);

        $dm = $this->get('doctrine_mongodb');
        $translationRepo = $dm->getRepository('TranslateBundle:Translation');
        $translation = $translationRepo->findByKey($key);

        $dm = $dm->getManager();

        foreach ($translation as $t) {
            $dm->remove($t);
        }

        $dm->flush();

        $view = $this->view($translation);

        return $this->handleView($view);
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
        $language = $this->getLang($lang, true);

        $view = $this->view($language);

        return $this->handleView($view);
    }
}
