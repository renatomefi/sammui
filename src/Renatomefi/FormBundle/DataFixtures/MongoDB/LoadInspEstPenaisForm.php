<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Renatomefi\FormBundle\Document\FormPage;
use Renatomefi\TranslateBundle\DataFixtures\MongoDB\LoadLangs;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;
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
     * Form pages
     * @var array
     */
    private static $pages = [
        [
            'title' => 'Estrutura Organizacional',
            'number' => 1
        ],
        [
            'title' => 'Identificação do Estabelecimento',
            'number' => 2
        ],
        [
            'title' => 'Administração',
            'number' => 3
        ],
        [
            'title' => 'Características do Estabelecimento',
            'number' => 4
        ],
        [
            'title' => 'Características das Pessoas Presas',
            'number' => 5
        ],
        [
            'title' => 'Características das Pessoas cumprindo Medida Segurança',
            'number' => 6
        ],
        [
            'title' => 'Características dos Funcionários em Exercício no Estabelecimento',
            'number' => 7
        ],
        [
            'title' => 'Condições Materiais',
            'number' => 8
        ],
        [
            'title' => 'Alimentação',
            'number' => 9
        ],
        [
            'title' => 'Rotina padrão',
            'number' => 10
        ],
        [
            'title' => 'Assistência à Saúde',
            'number' => 11
        ],
        [
            'title' => 'Assistência à Saúde',
            'number' => 12
        ],
        [
            'title' => 'Assistência Jurídica',
            'number' => 13
        ],
        [
            'title' => 'Assistência Laboral',
            'number' => 14
        ],
        [
            'title' => 'Assistências Educacionais/Desportivas/Culturais e de Lazer',
            'number' => 15
        ],
        [
            'title' => 'Assistência Religiosa',
            'number' => 16
        ],
        [
            'title' => 'Assistência Social',
            'number' => 17
        ],
        [
            'title' => 'Segurança',
            'number' => 18
        ],
        [
            'title' => 'Disciplina e ocorrências',
            'number' => 19
        ],
        [
            'title' => 'Visitas',
            'number' => 20
        ],
        [
            'title' => 'Relato das pessoas presas ou de funcionários',
            'number' => 21
        ],
        [
            'title' => 'Diversos',
            'number' => 22
        ],
        [
            'title' => 'Inspeções',
            'number' => 23
        ],
        [
            'title' => 'Valoração sobre os itens inspecionados',
            'number' => 24
        ],

    ];


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

        foreach (self::$pages as $page) {
            $formPage = new FormPage();
            $formPage->setNumber($page['number']);
            $formPage->setTitle($page['title']);
            $form->addPage($formPage);
            unset($formPage);
        }

        $translation = new Translation();
        /** @var Language $lang */
        $lang = $this->getReference(LoadLangs::$reference_prefix . 'pt-br');
        $translation->setLanguage($lang);
        $translation->setKey(static::NAME);
        $translation->setValue('Relatório de inspeção em Estabelecimentos Penais');

        $documentManager->persist($translation);
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