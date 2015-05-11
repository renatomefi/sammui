<?php

namespace Renatomefi\FormBundle\Controller;

use Doctrine\ODM\MongoDB\Query\Expr;
use FOS\RestBundle\Controller\FOSRestController;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolComment;
use Renatomefi\FormBundle\Document\ProtocolFieldValue;
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

        if (!$result && true === $notFoundException)
            throw $this->createNotFoundException("No Protocol found with id: \"$id\"");

        return $result;
    }

    /**
     * @param null $id
     * @param bool $notFoundException
     * @return mixed
     */
    protected function getForm($id, $notFoundException = false)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        $form = $formsDM->findOneById($id);

        if (TRUE === $notFoundException && !$form) {
            throw $this->createNotFoundException("Form not found: $id");
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
            ->select('id', 'createdAt')
            ->field('form')->references($form)
            ->getQuery()
            ->execute()
            ->toArray();

        if (!$result)
            throw $this->createNotFoundException("No protocols found for form: \"$formId\"");

        return $result;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        return $this->getProtocol($id);
    }

    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction($formId)
    {
        $form = $this->getForm($formId, true);

        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $protocol = new Protocol();
        $protocol->setForm($form);

        $dm->persist($protocol);
        $dm->flush();

        return ['id' => $protocol->getId()];
    }

    /**
     * @param $protocolId
     * @param $userName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAddUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $userDm = $dm->getRepository('UserBundle:User');
        $user = $userDm->findOneByUsername($userName);

        if (null === $user) {
            if (!$protocol->getOneNonUser($userName)) {
                $nonUser = new ProtocolUser();
                $nonUser->setUsername($userName);
                $protocol->addNonUser($nonUser);
            }
        } else {
            if (!$protocol->getUser()->contains($user))
                $protocol->addUser($user);
        }

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();
        unset($protocol);

        $protocol = $this->getProtocol($protocolId);

        return ['user' => $protocol->getUser(), 'nonUser' => $protocol->getNonUser()];
    }

    /**
     * @param Request $request
     * @param $protocolId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchFieldsSaveAction(Request $request, $protocolId)
    {
        $odm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $formFieldDM = $odm->getRepository('FormBundle:FormField');

        foreach ($request->get('data') as $item) {

            if (!array_key_exists('value', $item))
                continue;

            $currentValue = $protocol->getFieldValueByFieldId($item['id']);

            // If there is a FieldValue just update it, if not create a new one and add it!
            if ($currentValue) {

                // No changes? Will not update it and lastUpdate!
                if ($currentValue->getValue() === $item['value'])
                    continue;

                $currentValue->setLastUpdated(new \MongoDate());
                $currentValue->setValue($item['value']);
            } else {
                if ($item['value'] === null)
                    continue;

                $field = $formFieldDM->find($item['id']);
                $value = new ProtocolFieldValue();
                $value->setField($field);
                $value->setValue($item['value']);
                $protocol->addFieldValue($value);
            }

        }

        if (!$protocol->getFirstSaveDate()) {
            $protocol->setFirstSaveDate(new \MongoDate());
        }

        $protocol->setLastSaveDate(new \MongoDate());

        $odm->persist($protocol);
        $odm->flush();
        $odm->clear();

        $result = $odm->createQueryBuilder('FormBundle:Protocol')
            ->select('fieldValues')
            ->field('id')->equals($protocol->getId())
            ->getQuery()
            ->getSingleResult();

        return ['field_values' => $result->getFieldValues()];
    }

    /**
     * @param $protocolId
     * @param $userName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchRemoveUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        if ($nonUser = $protocol->getOneNonUser($userName))
            $protocol->removeNonUser($nonUser);

        if ($user = $protocol->getOneUser($userName))
            $protocol->removeUser($user);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();
        unset($protocol);

        $protocol = $this->getProtocol($protocolId);

        return ['user' => $protocol->getUser(), 'nonUser' => $protocol->getNonUser()];
    }

    /**
     * @param Request $request
     * @param $protocolId
     */
    public function patchConclusionAction(Request $request, $protocolId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);
        $protocol->setConclusion($request->get('conclusion'));

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();
    }

    /**
     * @param Request $request
     * @param $protocolId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAddCommentAction(Request $request, $protocolId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $comment = new ProtocolComment();
        $comment->setBody($request->get('body'));

        $protocol->addComment($comment);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();

        return $this->getProtocol($protocolId)->getComment()->toArray();
    }

    /**
     * @param $protocolId
     * @param $commentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchRemoveCommentAction($protocolId, $commentId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $comment = $protocol->getOneComment($commentId);

        if (!$comment)
            throw $this->createNotFoundException("Comment '$commentId' not found in protocol '$protocolId'");

        $protocol->removeComment($comment);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();

        return $this->getProtocol($protocolId)->getComment()->toArray();
    }
}
