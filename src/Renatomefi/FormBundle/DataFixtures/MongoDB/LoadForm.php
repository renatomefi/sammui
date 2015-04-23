<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Renatomefi\FormBundle\Document\FormPage;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadForm
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadForm extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * Form name
     */
    const NAME = 'sammui-form-demo';

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

        $form = new Form();
        $form->setName(static::NAME);
        $form->setTemplate(static::NAME);

        $page = new FormPage();
        $page->setNumber(1);
        $page->setTitle('First page');
        $form->addPage($page);
        unset($page);

        $page = new FormPage();
        $page->setNumber(2);
        $page->setTitle('Second page');
        $form->addPage($page);
        unset($page);

        $field = new FormField();
        $field->setName('Name');
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('Email');
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('Gender');
        $field->setOptions(['M','F']);
        $form->addField($field);
        unset($field);

        $documentManager->persist($form);
        $documentManager->flush();

        $this->addReference(static::NAME, $form);

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}