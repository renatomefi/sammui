<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadForm
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadFormFields extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $field->setName('name');
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $field = new FormField();
        $field->setName('nmail');
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $field = new FormField();
        $field->setName('gender');
        $field->setOptions(['m' => 'Masculino','f' => 'Feminino']);
        $field->setForm($form);
        $documentManager->persist($field);
        unset($field);

        $field = new FormField();
        $field->setName('above_21');
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