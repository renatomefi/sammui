<?php

namespace PensandoODireito\SisdepenFormsBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormPage;
use Renatomefi\TranslateBundle\DataFixtures\MongoDB\LoadLangs;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\TranslateBundle\Document\Translation;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Carrega os formulário do RELATÓRIO DE INSPEÇÃO EM ESTABELECIMENTOS PENAIS 1 2
 *
 * Class LoadFor
 * @package PensandoODireito\SisdepenFormsBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadForm extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * Form name
     */
    const NAME = 'inspecao-estabelecimentos-penais';

    const GROUP_ANUAL = 'anual';
    const GROUP_SEMESTRAL = 'semestral';
    const GROUP_MENSAL = 'mensal';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Form pages
     * @var array
     */
    public static $pages = [
        [
            'title' => 'Estrutura Organizacional',
            'number' => 1,
            'group' => LoadForm::GROUP_ANUAL
        ],
        [
            'title' => 'Identificação do Estabelecimento',
            'number' => 2,
            'group' => LoadForm::GROUP_ANUAL
        ],
        [
            'title' => 'Administração',
            'number' => 3,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Características do Estabelecimento',
            'number' => 4,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Características das Pessoas Presas',
            'number' => 5,
            'group' => LoadForm::GROUP_MENSAL
        ],
        [
            'title' => 'Características das Pessoas cumprindo Medida Segurança',
            'number' => 6,
            'group' => LoadForm::GROUP_MENSAL
        ],
        [
            'title' => 'Características dos Funcionários em Exercício no Estabelecimento',
            'number' => 7,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Condições Materiais',
            'number' => 8,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Alimentação',
            'number' => 9,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Rotina padrão',
            'number' => 10,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistência à Saúde',
            'number' => 11,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistência à Saúde',
            'number' => 12,
            'group' => LoadForm::GROUP_ANUAL
        ],
        [
            'title' => 'Assistência Jurídica',
            'number' => 13,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistência Laboral',
            'number' => 14,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistências Educacionais/Desportivas/Culturais e de Lazer',
            'number' => 15,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistência Religiosa',
            'number' => 16,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Assistência Social',
            'number' => 17,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Segurança',
            'number' => 18,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Disciplina e ocorrências',
            'number' => 19,
            'group' => LoadForm::GROUP_MENSAL
        ],
        [
            'title' => 'Visitas',
            'number' => 20,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Relato das pessoas presas ou de funcionários',
            'number' => 21,
            'group' => LoadForm::GROUP_MENSAL
        ],
        [
            'title' => 'Diversos',
            'number' => 22,
            'group' => LoadForm::GROUP_SEMESTRAL
        ],
        [
            'title' => 'Inspeções',
            'number' => 23,
            'group' => LoadForm::GROUP_MENSAL
        ],
        [
            'title' => 'Valoração sobre os itens inspecionados',
            'number' => 24,
            'group' => LoadForm::GROUP_SEMESTRAL
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
            $formPage->setGroup($page['group']);
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
        return 5;
    }
}