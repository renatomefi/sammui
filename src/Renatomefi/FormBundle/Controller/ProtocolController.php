<?php

namespace Renatomefi\FormBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\Protocol;
use Symfony\Component\HttpFoundation\Request;

class ProtocolController extends FOSRestController
{

    public function getForm($id = null, $notFoundException = false)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        if (null == $id) {
            $form = $formsDM->findAll();
            $exception = 'No Forms found';
        } else {
            $form = $formsDM->findOneById($id);
            $exception = 'Form not found: ' . $id;
        }

        if (TRUE == $notFoundException && !$form) {
            throw $this->createNotFoundException($exception);
        }

        return $form;
    }

    public function getFormAction($formId)
    {
        $form = $this->getForm($formId, true);

        $protocolDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $protocolDM
            ->createQueryBuilder()
            ->hydrate(false)
            ->field('form')->references($form)
            ->field('id')->hydrate(true)
            ->getQuery()
            ->execute()
            ->toArray();

//        var_dump(get_class($protocolDM->createQueryBuilder())); exit();
        if (!$result)
            throw $this->createNotFoundException("No protocols found for form: \"$formId\"");

        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function getAction($id)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $formsDM->findOneById($id);

        if (!$result)
            throw $this->createNotFoundException("No Protocol found with id: \"$id\"");

        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function postAction($formId)
    {
        $form = $this->getForm($formId, true
        );

        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = new Protocol();
        $protocol->setForm($form);
        $protocol->setCreatedAt(new \MongoDate());

        $dm->persist($protocol);
        $dm->flush();

        $view = $this->view($protocol);

        return $this->handleView($view);
    }
}
