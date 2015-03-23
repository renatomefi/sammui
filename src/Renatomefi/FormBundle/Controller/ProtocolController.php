<?php

namespace Renatomefi\FormBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Query;
use FOS\RestBundle\Controller\FOSRestController;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProtocolController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolController extends FOSRestController
{

    /**
     * @param $id
     * @param bool $notFoundException
     * @throws NotFoundHttpException
     * @return Protocol
     */
    protected function getProtocol($id, $notFoundException = true)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $formsDM->findOneById($id);

        if (!$result)
            throw $this->createNotFoundException("No Protocol found with id: \"$id\"");

        return $result;
    }

    /**
     * @param null $id
     * @param bool $notFoundException
     * @return mixed
     */
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

        if (TRUE === $notFoundException && !$form) {
            throw $this->createNotFoundException($exception);
        }

        return $form;
    }

    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

        if (!$result)
            throw $this->createNotFoundException("No protocols found for form: \"$formId\"");

        $view = $this->view($result);
        return $this->handleView($view);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        $view = $this->view($this->getProtocol($id));
        return $this->handleView($view);
    }

    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction($formId)
    {
        $form = $this->getForm($formId, true);

        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = new Protocol();
        $protocol->setForm($form);
        $protocol->setCreatedAt(new \MongoDate());

        $dm->persist($protocol);
        $dm->flush();

        $view = $this->view($protocol);

        return $this->handleView($view);
    }

    public function patchRemoveUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $protocolDm = $dm->getRepository('FormBundle:Protocol');

        $protocol = $this->getProtocol($protocolId);

        $result = $protocolDm
            ->createQueryBuilder()
            ->update()
            ->field('id')->equals($protocolId)
            ->field('user')->pull(array('username' => $userName))
            ->field('nonUser')->pull(array('username' => $userName))
            ->multiple(true)
            ->getQuery()
            ->execute();

        $view = $this->view($result);

        return $this->handleView($view);
    }

    public function patchAddUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocolDm = $dm->getRepository('FormBundle:Protocol');
        $protocol = $this->getProtocol($protocolId);

        $userDm = $dm->getRepository('UserBundle:User');
        $user = $userDm->findOneByUsername($userName);

        if (null === $user) {
            $nonUser = new ProtocolUser();
            $nonUser->setUsername($userName);
            $protocol->addNonUser($nonUser);
        } else {
            if (!$protocol->getUser()->contains($user))
                $protocol->addUser($user);
        }

        $dm->persist($protocol);
        $dm->flush();

        $view = $this->view($protocol);

        return $this->handleView($view);
    }
}
