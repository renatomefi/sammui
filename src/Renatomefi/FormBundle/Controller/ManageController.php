<?php

namespace Renatomefi\FormBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Renatomefi\FormBundle\Document\Form;
use Symfony\Component\HttpFoundation\Request;

class ManageController extends FOSRestController
{

    /**
     * @Get("/all/forms")
     */
    public function getAllAction()
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        $result = $formsDM->findAll();

        if (!$result)
            throw $this->createNotFoundException("No forms registered");

        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function getAction($id)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        $result = $formsDM->findOneById($id);

        if (!$result)
            throw $this->createNotFoundException("No form found with id: \"$id\"");

        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function postAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $form = new Form();
        $form->setName($request->get('name'));
        $form->setCreatedAt(new \MongoDate());

        $dm->persist($form);
        $dm->flush();

        $view = $this->view($form);

        return $this->handleView($view);
    }
}
