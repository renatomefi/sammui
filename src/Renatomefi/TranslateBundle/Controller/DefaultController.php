<?php

namespace Renatomefi\TranslateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/l10n")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{lang}", requirements={"lang" = "\w+"})
     */
    public function indexAction($lang)
    {
        return $this->render('TranslateBundle:Default:index.html.twig', array('name' => $lang));
    }
}
