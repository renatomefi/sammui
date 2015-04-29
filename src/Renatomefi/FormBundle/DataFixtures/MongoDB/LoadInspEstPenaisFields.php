<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Renatomefi\TranslateBundle\DataFixtures\MongoDB\LoadLangs;
use Renatomefi\TranslateBundle\Document\Translation;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Carrega os campos do formulário INSPEÇÃO EM ESTABELECIMENTOS PENAIS LoadInspEstPenaisForm
 * Class LoadInspEstPenaisFields
 * @package Renatomefi\UserBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadInspEstPenaisFields extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    protected static $fields = [
        [
            'name' => '1',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Esfera'
            ],
            'options' => [
                'estadual' => 'Estadual',
                'federal' => 'Federal',
            ],
            'type' => 'select'
        ],
        [
            'name' => '2',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Secretaria da pasta'
            ],
            'options' => [
                'estadual' => 'Própria',
                'subsecretaria' => 'Subsecretaria',
                'diretoria/departamento' => 'Diretoria / Departamento',
                'superintendência' => 'Superintendência',
                'instituto/agência' => 'Instituto / Agência',
                'outro' => 'Outro',
            ],
            'type' => 'select'
        ],
        [
            'name' => '3',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Unidade do MP / Defensoria'
            ],
            'type' => 'text'
        ],
        [
            'name' => '4',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Tribunal de Justiça'
            ],
            'type' => 'text'
        ],
        [
            'name' => '5',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Grau de Jurisdição'
            ],
            'type' => 'text'
        ],
        [
            'name' => '6',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Comarca'
            ],
            'type' => 'text'
        ],
        [
            'name' => '7',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Há Escola Penitenciária Estadual?'
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '8',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Há Ouvidoria Estadual do Sistema Prisional?'
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '9',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Há Corregedoria Estadual do Sistema Prisional?'
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '10',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Há Plano de Carreira?'
            ],
            'options' => [
                'sim' => 'Sim', // Exibir: Todos os servidores penitenciarios , Agentes penitenciarios , Outro
                'não' => 'Não',
            ],
            'type' => 'radio'
        ],
        [
            'name' => '11',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Há Plano Estadual de Educação do Sistema Penitenciário?'
            ],
            'type' => 'boolean'
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
     * Return field name
     * @param array $field
     * @return string
     */
    private static function getFieldName(array $field)
    {
        $name = '';

        if (array_key_exists('page', $field)) {
            $name .= $field['page'] . '_';
        }

        $name .= $field['name'];

        return $name;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $documentManager = $this->container->get('doctrine_mongodb.odm.document_manager');

        /** @var Form $form */
        $form = $this->getReference(LoadInspEstPenaisForm::NAME);

        $fieldPrefix = 'form-' . $form->getName();

        foreach (self::$fields as $field) {
            $formField = new FormField();
            $formField->setForm($form);

            $formField->setName(self::getFieldName($field));

            if (array_key_exists('options', $field)) {
                $formField->setOptions($field['options']);
            }

            $documentManager->persist($formField);
            unset($formField);

            $translation = new Translation();
            $translation->setKey($fieldPrefix . '-field-' . self::getFieldName($field));
            $translation->setLanguage($this->getReference(LoadLangs::$reference_prefix . 'pt-br'));
            $translation->setValue($field['translate']['pt-br']);
            $documentManager->persist($translation);

            unset($translation);
        }

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