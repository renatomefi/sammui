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
 * Carrega os formulário do RELATÓRIO DE INSPEÇÃO EM ESTABELECIMENTOS PENAIS 1 2
 * Class LoadInspEstPenaisForm
 *
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadInspEstPenaisForm extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * Form name
     */
    const NAME = 'inspecao-estabelecimentos-penais';

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

        /**
         * Estrutura Organizacional
         */
        $page = new FormPage();
        $page->setNumber(1);
        $page->setTitle('Estrutura Organizacional');
        $form->addPage($page);
        unset($page);

        /**
         * Identificação do Estabelecimento
         */
        $page = new FormPage();
        $page->setNumber(2);
        $page->setTitle('Identificação do Estabelecimento');
        $form->addPage($page);
        unset($page);

        /**
         * Administração
         */
        $page = new FormPage();
        $page->setNumber(3);
        $page->setTitle('Administração');
        $form->addPage($page);
        unset($page);

        /**
         * Características do Estabelecimento
         */
        $page = new FormPage();
        $page->setNumber(4);
        $page->setTitle('Características do Estabelecimento');
        $form->addPage($page);
        unset($page);

        /**
         * Características das Pessoas Presas
         */
        $page = new FormPage();
        $page->setNumber(5);
        $page->setTitle('Características das Pessoas Presas');
        $form->addPage($page);
        unset($page);

        /**
         * Características das Pessoas cumprindo Medida Segurança
         */
        $page = new FormPage();
        $page->setNumber(6);
        $page->setTitle('Características das Pessoas cumprindo Medida Segurança');
        $form->addPage($page);
        unset($page);

        /**
         * Características dos Funcionários em Exercício no Estabelecimento
         */
        $page = new FormPage();
        $page->setNumber(7);
        $page->setTitle('Características dos Funcionários em Exercício no Estabelecimento');
        $form->addPage($page);
        unset($page);

        /**
         * Condições Materiais
         */
        $page = new FormPage();
        $page->setNumber(8);
        $page->setTitle('Condições Materiais');
        $form->addPage($page);
        unset($page);

        /**
         * Alimentação
         */
        $page = new FormPage();
        $page->setNumber(9);
        $page->setTitle('Alimentação');
        $form->addPage($page);
        unset($page);

        /**
         * Rotina padrão
         */
        $page = new FormPage();
        $page->setNumber(10);
        $page->setTitle('Rotina padrão');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência à Saúde
         */
        $page = new FormPage();
        $page->setNumber(11);
        $page->setTitle('Assistência à Saúde');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência à Saúde
         */
        $page = new FormPage();
        $page->setNumber(12);
        $page->setTitle('Assistência à Saúde');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência Jurídica
         */
        $page = new FormPage();
        $page->setNumber(13);
        $page->setTitle('Assistência Jurídica');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência Laboral
         */
        $page = new FormPage();
        $page->setNumber(14);
        $page->setTitle('Assistência Laboral');
        $form->addPage($page);
        unset($page);

        /**
         * Assistências Educacionais/Desportivas/Culturais e de Lazer
         */
        $page = new FormPage();
        $page->setNumber(15);
        $page->setTitle('Assistências Educacionais/Desportivas/Culturais e de Lazer');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência Religiosa
         */
        $page = new FormPage();
        $page->setNumber(16);
        $page->setTitle('Assistência Religiosa');
        $form->addPage($page);
        unset($page);

        /**
         * Assistência Social
         */
        $page = new FormPage();
        $page->setNumber(17);
        $page->setTitle('Assistência Social');
        $form->addPage($page);
        unset($page);

        /**
         * Segurança
         */
        $page = new FormPage();
        $page->setNumber(18);
        $page->setTitle('Segurança');
        $form->addPage($page);
        unset($page);

        /**
         * Disciplina e ocorrências
         */
        $page = new FormPage();
        $page->setNumber(19);
        $page->setTitle('Disciplina e ocorrências');
        $form->addPage($page);
        unset($page);

        /**
         * Visitas
         */
        $page = new FormPage();
        $page->setNumber(20);
        $page->setTitle('Visitas');
        $form->addPage($page);
        unset($page);

        /**
         * Relato das pessoas presas ou de funcionários
         */
        $page = new FormPage();
        $page->setNumber(21);
        $page->setTitle('Relato das pessoas presas ou de funcionários');
        $form->addPage($page);
        unset($page);

        /**
         * Diversos
         */
        $page = new FormPage();
        $page->setNumber(22);
        $page->setTitle('Diversos');
        $form->addPage($page);
        unset($page);

        /**
         * Inspeções
         */
        $page = new FormPage();
        $page->setNumber(23);
        $page->setTitle('Inspeções');
        $form->addPage($page);
        unset($page);

        /**
         * Valoração sobre os itens inspecionados
         */
        $page = new FormPage();
        $page->setNumber(24);
        $page->setTitle('Valoração sobre os itens inspecionados');
        $form->addPage($page);
        unset($page);

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