<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormPage;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadForm
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadForm implements FixtureInterface, ContainerAwareInterface
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

        $documentManager->persist($form);
        $documentManager->flush();

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}