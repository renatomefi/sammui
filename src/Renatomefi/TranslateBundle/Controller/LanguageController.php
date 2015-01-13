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

    /**
     * @Route("/test/{lang}")
     */
    public function testAction($lang)
    {
        $dm = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
//        $product = $dm->findAll();
        $language = $dm->findOneBy(array('key' => $lang));


        if (!$language) {
            throw $this->createNotFoundException('No product found for lang ' . $lang);
        } else {
            \Doctrine\Common\Util\Debug::dump($language);
            \Doctrine\Common\Util\Debug::dump($dm->findAll());
//            \Doctrine\Common\Util\Debug::dump($product->getLastUpdate());
        }

//        var_dump($dm->getRepository('TranslateBundle:Language'));
//        var_dump($dm->getRepository('TranslateBundle:Language')->find());
//        var_dump($response);

        return $this->render('TranslateBundle:Default:index.html.twig', ['name' => $lang]);
    }
}
