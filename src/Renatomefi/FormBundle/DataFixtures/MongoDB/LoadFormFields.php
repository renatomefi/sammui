<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadForm
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadFormFields extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $documentManager = $this->container->get('doctrine_mongodb.odm.document_manager');

        /** @var Form $form */
        $form = $this->getReference(LoadForm::NAME);

        $field = new FormField();
        $field->setName('Name');
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $field = new FormField();
        $field->setName('Email');
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $field = new FormField();
        $field->setName('Gender');
        $field->setOptions(['M','F']);
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $documentManager->flush();

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}