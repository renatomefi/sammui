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
    public function getLang($lang = null, $notFoundException = false)
    {
        $languageDM = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');

        if (null == $lang) {
            $language = $languageDM->findAll();
            $exception = 'No Languages found';
        } else {

            $language = $languageDM->findOneByKey($lang);
            $exception = 'Language not found: ' . $lang;
        }

        if (TRUE == $notFoundException && !$language) {
            throw $this->createNotFoundException($exception);
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
        $language = $this->getLang($lang, true);

        $translationRepo = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Translation');

        $result = $translationRepo->createQueryBuilder('Translation')
            ->field('language')->references($language)
            ->field('key')->equals($key)
            ->getQuery()
            ->getSingleResult();

        if (!$result) {
            throw $this->createNotFoundException("No key \"$key\" found for lang \"$lang\"");
        }

        $view = $this->view($result);

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
        $view = $this->view($this->getLang(null, true));

        return $this->handleView($view);
    }

    public function getLangAction($lang)
    {
        $view = $this->view($this->getLang($lang, true));

        return $this->handleView($view);
    }
}
