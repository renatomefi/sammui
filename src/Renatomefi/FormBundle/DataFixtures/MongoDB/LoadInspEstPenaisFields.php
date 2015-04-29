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
        // BEGIN page 1
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
        // END page 1

        // BEGIN page 2
        [
            'name' => '1',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento'
            ],
            'type' => 'text'
        ],
        [
            'name' => '2',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Apelido da unidade'
            ],
            'type' => 'text'
        ],
        [
            'name' => '3',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Endereço'
            ],
            'type' => 'text'
        ],
        [
            'name' => '4',
            'page' => '2',
            'translate' => [
                'pt-br' => 'CEP'
            ],
            'type' => 'text'
        ],
        [
            'name' => '5',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Cidade/UF'
            ],
            'type' => 'text'
        ],
        [
            'name' => '6',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado ao recolhimento de presos provisórios'
            ],
            // helper  Ex: Cadeia Pública; Presídio; Centro de Detenção Provisória; Unidade de Recolhimento Provisório
            'type' => 'boolean'
        ],
        [
            'name' => '7',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado ao cumprimento de pena em regime fechado'
            ],
            // helper  Ex: Penitenciária
            'type' => 'boolean'
        ],
        [
            'name' => '8',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado ao cumprimento de pena em regime semiaberto'
            ],
            // helper Ex: Colônias agrícolas, industriais ou similares; Centro de Progressão Penitenciária; Unidade de Regime Semiaberto; Centro de Integração Social
            'type' => 'boolean'
        ],
        [
            'name' => '9',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado ao cumprimento de regime aberto ou de limitação de fim de semana'
            ],
            // helper Ex: Casa de Albergado
            'type' => 'boolean'
        ],
        [
            'name' => '10',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado ao cumprimento de medida de segurança de internação ou tratamento ambulatoria'
            ],
            // helper Ex: Hospital de Custódia e Tratamento Psiquiátrico
            'type' => 'boolean'
        ],
        [
            'name' => '11',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado a diversos tipos de regime'
            ],
            // helper Ex: Centro de Ressocialização
            'type' => 'boolean'
        ],
        [
            'name' => '12',
            'page' => '2',
            'translate' => [
                'pt-br' => 'Estabelecimento destinado à realização de exames gerais e criminológico'
            ],
            // helper Ex: Centro de Observação Criminológica e Triagem
            'type' => 'boolean'
        ],
        [
            'name' => '13',
            'page' => '2',
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'radio',
            'options' => [
                'masculino' => 'Masculino',
                'feminino' => 'Feminino',
                'misto' => 'Misto'
            ],
        ],
        // END page 2

        // BEGIN page 3
        [
            'name' => '1',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Gestão'
            ],
            'type' => 'multicheckbox',
            'options' => [
                'pública' => 'Pública',
                'terceirização de serviços complementares' => 'Terceirização de serviços complementares',
                'terceirização da equipe técnica e administrativa' => 'Terceirização da equipe técnica e administrativa',
                'terceirização da equipe de segurança' => 'Terceirização da equipe de segurança',
                'método APAC' => 'Método APAC',
            ]
        ],
        [
            'name' => '2',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Responsável pelo estabelecimento'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Cargo'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Formação Profissional'
            ],
            'type' => 'select',
            'options' => [
                'Direito' => 'Direito',
                'Ciências Sociais' => 'Ciências Sociais',
                'Psicologia' => 'Psicologia',
                'Pedagogia' => 'Pedagogia',
                'Administração' => 'Administração',
                'Serviço Social' => 'Serviço Social',
                'Outra' => 'Outra',
            ]
        ],

        [
            'name' => '5',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Responsável pela segurança'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Cargo'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Formação Profissional'
            ],
            'type' => 'select',
            'options' => [
                'direito' => 'Direito',
                'ciências sociais' => 'Ciências Sociais',
                'psicologia' => 'Psicologia',
                'pedagogia' => 'Pedagogia',
                'administração' => 'Administração',
                'serviço social' => 'Serviço Social',
                'outra' => 'Outra',
            ]
        ],
        [
            'name' => '8',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Quantidade de computadores'
            ],
            'type' => 'select',
            'options' => [
                '1 a 3' => '1 a 3',
                '4 a 6' => '4 a 6',
                '7 a 9' => '7 a 9',
                '10 a 12' => '10 a 12',
                '13 a 15' => '13 a 15',
                '+15' => '+15',
            ]
        ],
        [
            'name' => '9',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Acesso a internet'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Alimenta o INFOPEN'
            ],
            'type' => 'select',
            'options' => [
                'Integralmente' => 'Integralmente',
                'Parcialmente' => 'Parcialmente',
                'Não alimenta' => 'Não alimenta',
                'Mensal' => 'Mensal',
                'Trimestral' => 'Trimestral',
                'Semestral' => 'Semestral',
                'Anual' => 'Anual',
                'Outro' => 'Outro',
            ]
        ],
        [
            'name' => '11',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Regulamento interno da unidade/Estado'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12',
            'page' => '3',
            'translate' => [
                'pt-br' => 'Regulamento disciplinar penitenciário da unidade/Estado'
            ],
            'type' => 'boolean',
        ],
        //END page 3

        //BEGIN page 4
        [
            'name' => '1',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Capacidade total'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Lotação total'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_1_1',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Condenada'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_1_2',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Provisória'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_2_1',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Condenada'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_2_2',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Provisória'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_3_1',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Condenada'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_3_2',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Provisória'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para diferentes regimes?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para presos provisórios e condenados?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para idosos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para mulheres, se for o caso?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para pessoas em medida de segurança?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '9',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há alas separadas para LGBT?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há local especial para cumprimento de seguro/custódia diferenciada?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '11',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há acessibilidade para pessoas com deficiência?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Há celas metálicas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '13',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Número de celas individuais Homens'
            ],
            'type' => 'text',
        ],
        [
            'name' => '14',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Número de celas individuais Mulheres'
            ],
            'type' => 'text',
        ],
        [
            'name' => '15',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Lotação celas individuais Homens'
            ],
            'type' => 'text',
        ],
        [
            'name' => '16',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Lotação celas individuais Mulheres'
            ],
            'type' => 'text',
        ],
        [
            'name' => '17',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Dimensão'
            ],
            'type' => 'text',
        ],
        [
            'name' => '18',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Permeabilidade'
            ],
            'type' => 'select',
            'options' => [
                '1 a 3%',
                '3 a 5%',
                '5 a 10%',
                '>10%',
            ]
        ],
        [
            'name' => '19',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Ventilação cruzada geral'
            ],
            'type' => 'select',
            'options' => [
                'Insuficiente',
                'Suficiente',
                'Excessiva',
            ]
        ],
        [
            'name' => '20',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Ventilação cruzada nas celas'
            ],
            'type' => 'select',
            'options' => [
                'Insuficiente',
                'Suficiente',
                'Excessiva',
            ]
        ],
        [
            'name' => '21',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Iluminação natural nas celas'
            ],
            'type' => 'select',
            'options' => [
                'Inexistente',
                'Existente',
            ]
        ],
        [
            'name' => '22',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Incidência de sol nas celas'
            ],
            'type' => 'select',
            'options' => [
                'Insuficiente',
                'Suficiente',
                'Excessiva',
            ]
        ],
        [
            'name' => '23',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Programa de combate a incêndio'
            ],
            'type' => 'select',
            'options' => [
                'Inexistente',
                'Existente',
            ]
        ],
        [
            'name' => '24',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Extintores de incêndio'
            ],
            'type' => 'select',
            'options' => [
                'Insuficiente',
                'Suficiente',
            ]
        ],
        [
            'name' => '25',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Extintores de incêndio'
            ],
            'type' => 'select',
            'options' => [
                'Sem condições de uso',
                'Em condições de uso',
            ]
        ],
        [
            'name' => '26',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Construído ou ampliado com subvenção de recursos federais?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '27',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Reformado com subvenção de recursos federais?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '28',
            'page' => '4',
            'translate' => [
                'pt-br' => 'Indicativos da atuação de facções no estabelecimento?'
            ],
            'type' => 'boolean',
            // Quais???
        ],

        //END page 4

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