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
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('email');
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('gender');
        $field->setOptions(['m' => 'Masculino', 'f' => 'Feminino']);
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('above_21');
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('sammui_uses');
        $field->setOptions(['PHP', 'Symfony 2', 'Javascript', 'AngularJS', 'MongoDB', 'Mobile Angular UI']);
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('should_open_next');
        $documentManager->persist($field);
        $form->addField($field);

        $nField = new FormField();
        $nField->setName('next');
        $nField->addDependsOn($field);
        $documentManager->persist($nField);
        $form->addField($nField);
        unset($field);
        unset($nField);

        $field = new FormField();
        $field->setName('operational_system');
        $field->setOptions(['Linux', 'MacOS', 'Windows', 'Other']);
        $field->setFreeTextOption(3);
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $field = new FormField();
        $field->setName('operational_system_map');
        $field->setOptions(['linux' => 'Linux', 'mac' => 'MacOS', 'win' => 'Windows', 'other' => 'Other']);
        $field->setFreeTextOption('other');
        $documentManager->persist($field);
        $form->addField($field);
        unset($field);

        $documentManager->persist($form);
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