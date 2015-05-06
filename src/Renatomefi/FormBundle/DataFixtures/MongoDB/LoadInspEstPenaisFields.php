<?php

namespace Renatomefi\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Renatomefi\FormBundle\Document\Form;
use Renatomefi\FormBundle\Document\FormField;
use Renatomefi\FormBundle\Document\FormFieldDepends;
use Renatomefi\TranslateBundle\DataFixtures\MongoDB\LoadLangs;
use Renatomefi\TranslateBundle\Document\Language;
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
                'Própria',
                'Subsecretaria',
                'Diretoria / Departamento',
                'Superintendência',
                'Instituto / Agência',
                'other' => 'Outro',
            ],
            'type' => 'select'
        ],
        [
            'name' => '3_1',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Unidade do MP'
            ],
            'type' => 'text'
        ],
        [
            'name' => '3_2',
            'page' => '1',
            'translate' => [
                'pt-br' => 'Defensoria'
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
                'Sim', // Exibir: Todos os servidores penitenciarios , Agentes penitenciarios , Outro
                'Não',
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '10_1',
            'page' => '1',
            'depends_on' => [
                'field' => '1_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'options' => [
                'Todos os servidores penitenciarios',
                'Agentes penitenciarios',
                'other' => 'Outro',
            ],
            'type' => 'select'
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
                'Masculino',
                'Feminino',
                'Misto'
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
                'Pública',
                'Terceirização de serviços complementares',
                'Terceirização da equipe técnica e administrativa',
                'Terceirização da equipe de segurança',
                'Método APAC',
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
                'Direito',
                'Ciências Sociais',
                'Psicologia',
                'Pedagogia',
                'Administração',
                'Serviço Social',
                'other' => 'Outra',
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
                'Direito',
                'Ciências Sociais',
                'Psicologia',
                'Pedagogia',
                'Administração',
                'Serviço Social',
                'other' => 'Outra',
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
                '1 a 3',
                '4 a 6',
                '7 a 9',
                '10 a 12',
                '13 a 15',
                '+15',
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
                'Integralmente',
                'Parcialmente',
                'Não alimenta',
                'Mensal',
                'Trimestral',
                'Semestral',
                'Anual',
                'other' => 'Outro',
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
            'name' => '26_1',
            'page' => '4',
            'depends_on' => [
                'field' => '4_26',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quais'
            ],
            'type' => 'text',
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


        //BEGIN page 5
        [
            'name' => '1',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas com deficiência?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '1_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_1',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text'
        ],
        [
            'name' => '2',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas com mais de 60 anos presas?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '2_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_2',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text'
        ],
        [
            'name' => '3',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há indígenas presos?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '3_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_3',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text'
        ],
        [
            'name' => '4',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há notificação para Funai quanto ao ingresso do indígena?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há estrangeiros presos?'
            ],
            'type' => 'boolean',
            // Quantidade?
        ],
        [
            'name' => '5_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_3',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há adolescentes internados no local?'
            ],
            'type' => 'boolean',
            // Quantidade?
        ],
        [
            'name' => '6_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_6',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Os adolescentes estão separados dos adultos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Providências adotadas em relação à separação imediata e retirada do(s) adolescente(s)'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com transtorno mental?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '9_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_9',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '10',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas em tratamento para dependência química?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '10_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '11',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com Diabetes?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '11_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_11',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '12',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com Hipertensão?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '12_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_12',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '13',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com HIV?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '13_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_13',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '14',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com Hepatite?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '14_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_14',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '15',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com Tuberculose?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '15_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_15',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '16',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas com Hanseníase?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '16_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_16',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '17',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há pessoas presas em RDD?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '17_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_17',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '18',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há presas gestantes?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '18_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_18',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '19',
            'page' => '5',
            'translate' => [
                'pt-br' => 'Há crianças permanecendo com suas mães presas?'
            ],
            'type' => 'boolean',
            // Quantidade???
        ],
        [
            'name' => '19_1',
            'page' => '5',
            'depends_on' => [
                'field' => '5_19',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        //END page 5

        //BEGIN page 6
        [
            'name' => '1',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Quantidade de pessoas cumprindo medida de internação'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Quantidade de pessoas cumprindo medida ambulatorial'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Pacientes com mais tempo de internação'
            ],
            'type' => 'select',
            'options' => [
                //Quantidade?
                'até 1 ano',
                'de 1 a 3 anos',
                'de 4 a 6 anos',
                'de 7 a 9 anos',
                'de 10 a 20 anos',
                'de 21 a 30 anos',
                'mais que 30 anos',
            ]
        ],
        [
            'name' => '3_1',
            'page' => '6',
            'depends_on' => [
                'field' => '6_3',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Há pacientes com alta médica?'
            ],
            'type' => 'boolean',
            //Quantidade?
        ],
        [
            'name' => '4_1',
            'page' => '6',
            'depends_on' => [
                'field' => '6_4',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Pacientes indultados no último ano'
            ],
            'type' => 'boolean',
            //Quantidade?
        ],
        [
            'name' => '5_1',
            'page' => '6',
            'depends_on' => [
                'field' => '6_5',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Pacientes encaminhados no último ano para'
            ],
            'type' => 'select',
            'options' => [
                'Centro de Atenção Psicossocial - CAPS',
                'Serviços Residenciais Terapêuticos -SRTs',
                'Programa de Volta para Casa – PVC',
                'other' => 'Outro',
            ]
        ],
        [
            'name' => '7',
            'page' => '6',
            'translate' => [
                'pt-br' => 'Periodicidade do exame de cessação de periculosidade'
            ],
            'type' => 'select',
            'options' => [
                'Centro de Atenção Psicossocial - CAPS',
                'Serviços Residenciais Terapêuticos -SRTs',
                'Programa de Volta para Casa – PVC',
                'other' => 'Outro',
            ]
        ],
        //END page 6

        //BEGIN page 7

        [
            'name' => '1',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Total de RH na área de segurança'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Total de RH na área administrativa'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Total de RH na área técnica'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Total Geral'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Advogados / Defensores Públicos alocados na unidade'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="Defensoria Pública ">Defensoria Pública</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Outra forma de contratação">Outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '5_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_5',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_5',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'Defensoria Pública',
                'Própria Unidade',
                'other' => 'Outra forma de contratação',
            ]
        ],
        [
            'name' => '5_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_5',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '6',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Auxiliares de Enfermagem'
            ],
            'type' => 'boolean',
            /**
             *   <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '6_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_6',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_6',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '6_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_6',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '7',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Assistentes Sociais'
            ],
            'type' => 'boolean',
            /**
             *            <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '7_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_7',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_7',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUAS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '7_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_7',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '8',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Dentistas'
            ],
            'type' => 'boolean',
            /**
             *           <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '8_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '8_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '9',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Enfermeiros'
            ],
            'type' => 'boolean',
            /**
             *           <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '9_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_9',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_9',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '9_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_9',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '10',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Médicos - Clínico Geral'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '10_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '10_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '10_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '11',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Médicos - Psiquiatras'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '11_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_11',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '11_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_11',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '11_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_11',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '12',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Médicos - Ginecologista'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '12_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_12',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '12_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_12',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '12_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_12',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '13',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Pedagogos'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="Secretaria de Educação">Secretaria de Educação</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '13_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_13',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '13_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_13',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'Secretaria de Educação',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '13_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_13',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '14',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Psicólogos'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="SUAS">SUAS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '14_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_14',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '14_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_14',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'SUAS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '14_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_14',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '15',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Terapeutas Ocupacionais'
            ],
            'type' => 'boolean',
            /**
             *             <!-- Quantidade ?-->
             * <option value="SUS">SUS</option>
             * <option value="Própria Unidade">Própria Unidade</option>
             * <option value="Terceirizado/outra forma de contratação">Terceirizado/outra forma de contratação</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</option>
             */
        ],
        [
            'name' => '15_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_15',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '15_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_15',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'SUS',
                'Própria Unidade',
                'other' => 'Terceirizado/outra forma de contratação',
            ]
        ],
        [
            'name' => '15_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_15',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '16',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Outros'
            ],
            'type' => 'text',
            /**
             * <!-- Quantidade ?-->
             * <option value="Própria Unidade">Própria Unidade</option>
             * <!-- Periodicidade/ frequência -->
             * <option value="Mensal">Mensal</option>
             * <option value="Quinzenal">Quinzenal</option>
             * <option value="Semanal">Semanal</option>
             * <option value="Diária">Diária</optio
             */
        ],
        [
            'name' => '16_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_16',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '16_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_16',
                'value' => null
            ],
            'translate' => [
                'pt-br' => ''
            ],
            'type' => 'select',
            'options' => [
                'Própria Unidade',
            ]
        ],
        [
            'name' => '16_3',
            'page' => '7',
            'depends_on' => [
                'field' => '7_16',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],
        [
            'name' => '17',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Agentes Prisionais'
            ],
            'type' => 'boolean',
            /**
             * <!-- Quantidade ?-->
             * <option value="homens">homens</option>
             * <option value="mulheres">mulheres</option>
             */
        ],
        [
            'name' => '17_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_17',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade homens'
            ],
            'type' => 'text',
        ],
        [
            'name' => '17_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_17',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quantidade mulheres'
            ],
            'type' => 'text',
        ],
        [
            'name' => '18',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Escala de trabalho'
            ],
            'type' => 'text',
        ],
        [
            'name' => '19',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Há utilização de uniforme?'
            ],
            'type' => 'boolean',
            /**
             * <option value="Com identificação pessoal">Com identificação pessoal</option>
             */
        ],
        [
            'name' => '19_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_19',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Com identificação pessoal'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '20',
            'page' => '7',
            'translate' => [
                'pt-br' => 'Quais os tipos de cursos ocorrem para o treinamento dos agentes?'
            ],
            'type' => 'select',
            'options' => [
                'Curso de Formação',
                'Cursos Especiais',
            ]
        ],
        [
            'name' => '20_1',
            'page' => '7',
            'depends_on' => [
                'field' => '7_20',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Entidade Executora'
            ],
            'type' => 'text',
        ],
        [
            'name' => '20_2',
            'page' => '7',
            'depends_on' => [
                'field' => '7_20',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Periodicidade/ frequência'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Quinzenal',
                'Semanal',
                'Diária',
            ]
        ],

        //END page 7

        //BEGIN page 8
        [
            'name' => '1',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há camas e colchões para todos os presos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de uniformes?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '3',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de calçados?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '4',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de roupas de cama?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de toalhas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Periodicidade de substituição do material entregue'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de artigos de higiene pessoal?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7_1',
            'page' => '8',
            'depends_on' => [
                'field' => '8_7',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quais'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de artigos de limpeza?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8_1',
            'page' => '8',
            'depends_on' => [
                'field' => '8_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quais'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de absorventes para as mulheres?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica'
            ]
        ],
        [
            'name' => '10',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há distribuição de fraldas, se for o caso?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica'
            ]
        ],
        [
            'name' => '11',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há local destinado à venda de produtos e objetos permitidos e não fornecidos pela administração?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '11_1',
            'page' => '8',
            'depends_on' => [
                'field' => '8_11',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Descrever como é feito o pagamento, controle de preços e destino da receita'
            ],
            'type' => 'text',
        ],
        [
            'name' => '12',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Descrever a mobília que compõe as celas'
            ],
            'type' => 'text',
        ],
        [
            'name' => '13',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há sanitário e lavatório em todas as celas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '14',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Caso não haja instalações sanitárias na cela, como é garantido o acesso aos banheiros externos?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '15',
            'page' => '8',
            'translate' => [
                'pt-br' => 'É garantido o acesso ao banheiro no período noturno?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '16',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Número de pessoas por vaso sanitário'
            ],
            'type' => 'text',
        ],
        [
            'name' => '17',
            'page' => '8',
            'translate' => [
                'pt-br' => 'É garantido a qualquer momento o uso da descarga do vaso sanitário?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '18',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Há privacidade para uso das instalações sanitárias?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '19',
            'page' => '8',
            'translate' => [
                'pt-br' => 'Número de pessoas por chuveiro'
            ],
            'type' => 'text',
        ],
        [
            'name' => '20',
            'page' => '8',
            'translate' => [
                'pt-br' => 'É garantido o banho diário?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '21',
            'page' => '8',
            'translate' => [
                'pt-br' => 'A água é aquecida?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '22',
            'page' => '8',
            'translate' => [
                'pt-br' => 'É fornecida água potável?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '23',
            'page' => '8',
            'translate' => [
                'pt-br' => 'A água é racionada?'
            ],
            'type' => 'boolean',
            //Qual a frequência e duração oferecida?
        ],
        [
            'name' => '23_1',
            'page' => '8',
            'depends_on' => [
                'field' => '8_23',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Qual a frequência e duração oferecida?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '24',
            'page' => '8',
            'translate' => [
                'pt-br' => 'A água é racionada?'
            ],
            'type' => 'select',
            'options' => [
                'Hidráulico',
                'Elétrica',
                'Edificação',
                'other' => 'Outros',
            ]
        ],
        //END page 8

        //BEGIN page 9
        [
            'name' => '1',
            'page' => '9',
            'translate' => [
                'pt-br' => 'A alimentação é preparada na própria unidade?'
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '2',
            'page' => '9',
            'depends_on' => [
                'field' => '9_1',
                'value' => false
            ],
            'translate' => [
                'pt-br' => 'Em caso negativo, de onde provém Qual o custo diário da alimentação por preso?'
            ],
            'type' => 'text'
        ],
        [
            'name' => '3',
            'page' => '9',
            'translate' => [
                'pt-br' => 'O cardápio é orientado por nutricionista?'
            ],
            'type' => 'boolean'
        ],
        [
            'name' => '4',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Qual a quantidade de alimentação fornecida no almoço e janta à pessoa presa (peso)?'
            ],
            'type' => 'text'
        ],
        [
            'name' => '5',
            'page' => '9',
            'translate' => [
                'pt-br' => 'N.º de refeições diárias'
            ],
            'type' => 'text'
        ],
        [
            'name' => '6',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Horários das refeições'
            ],
            'type' => 'text'
        ],
        [
            'name' => '7',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Onde as refeições são realizadas?'
            ],
            'type' => 'select',
            'options' => [
                'Celas',
                'Refeitorio',
                'other' => 'Outro',
            ]
        ],
        [
            'name' => '8',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Há controle de qualidade?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '9',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Descrever o controle'
            ],
            'type' => 'text',
        ],
        [
            'name' => '10',
            'page' => '9',
            'translate' => [
                'pt-br' => 'As refeições são adaptadas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10_1',
            'page' => '9',
            'depends_on' => [
                'field' => '9_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Adaptadas por motivos de'
            ],
            'type' => 'select',
            'options' => [
                'Saúde',
                'Religiosos',
                'other' => 'Outros',
            ]
        ],
        [
            'name' => '11',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Os presos deslocados para audiências e outras atividades externas recebem alimentação e água potável quando saem e quando retornam, independentemente do horário?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12',
            'page' => '9',
            'translate' => [
                'pt-br' => 'Há outras formas de fornecimento de alimentos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12_1',
            'page' => '9',
            'depends_on' => [
                'field' => '9_12',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'As refeições são'
            ],
            'type' => 'select',
            'options' => [
                'Família',
                'Compra',
                'other' => 'Outro',
            ]
        ],
        //END page 9

        //BEGIN page 10
        [
            'name' => '1',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo diário dentro da cela'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de pátio de sol'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_2',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de visita'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_3',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de atividades educacionais'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_4',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de atividades laborais'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_5',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de atividades religiosas'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_6',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de visita íntima'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_7',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo de atividades esportivas'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Tempo das atividades culturais'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_9',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '10',
            'page' => '10',
            'translate' => [
                'pt-br' => 'Há programa individualizado para o cumprimento da pena?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10_1',
            'page' => '10',
            'depends_on' => [
                'field' => '10_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Qual a freqüência de atualização'
            ],
            'type' => 'select',
            'options' => [
                'Mensal',
                'Trimestral',
                'Semestral',
                'other' => 'Outro',
            ]
        ],
        [
            'name' => '10_2',
            'page' => '10',
            'depends_on' => [
                'field' => '10_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quais profissionais participam da elaboração do programa'
            ],
            'type' => 'text',
        ],
        [
            'name' => '10_3',
            'page' => '10',
            'depends_on' => [
                'field' => '10_10',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Descreva os procedimentos para elaboração do programa individualizado'
            ],
            'type' => 'text',
        ],
        //END page 10

        //BEGIN page 11
        [
            'name' => '1',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Existe unidade básica de saúde do SUS?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Está integrado à Rede Cegonha do SUS?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '3',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há distribuição de preservativos?'
            ],
            'type' => 'boolean',
            //frequencia
        ],
        [
            'name' => '3_1',
            'page' => '11',
            'depends_on' => [
                'field' => '11_3',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Frequência'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há acesso às medicações definidas pelo SUS para farmácias de unidades prisionais?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há acesso às medicações prescritas que não estão no pacote SUS?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há exames e consultas de ingresso?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há pré-natal para presas gestantes?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há vacinação regular?'
            ],
            'type' => 'boolean',
            // Se sim, quais vacinas são oferecidas?
        ],
        [
            'name' => '8_1',
            'page' => '11',
            'depends_on' => [
                'field' => '11_8',
                'value' => null
            ],
            'translate' => [
                'pt-br' => 'Quais vacinas são oferecidas?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9',
            'page' => '11',
            'translate' => [
                'pt-br' => 'As pessoas presas têm acesso a médico particular, caso haja a contratação deste profissional por seus familiares?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '11',
            'translate' => [
                'pt-br' => 'As pessoas presas têm acesso aos exames médicos necessários?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '11',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Quais trabalhos são realizados para prevenção ou controle de doenças infecto-contagiosas?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '12',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Há ambulância na unidade?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '13',
            'page' => '11',
            'translate' => [
                'pt-br' => 'Para que estabelecimentos da rede de saúde as pessoas presas tem acesso, quando necessário?'
            ],
            'type' => 'select',
            'options' => [
                'Unidade Básica de Saúde – UBS',
                'Unidade de Pronto Atendimento – UPA',
                'Hospital',
                'Centro de Atendimento Psicossocial – CAPS',
                'other' => 'Outro',
            ]
        ],
        //END page 11

        //BEGIN page 12
        //END page 12

        //BEGIN page 13
        [
            'name' => '1',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Às pessoas presas sem condições financeiras é proporcionada assistência jurídica gratuita e permanente?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'depends_on' => [
                'field' => '13_1',
                'value' => null
            ],
            'page' => '13',
            'translate' => [
                'pt-br' => 'Em caso positivo, por quem é prestada a assistência?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '13',
            'translate' => [
                'pt-br' => 'A Funai presta assistência jurídica aos presos/internos indígenas?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica',
            ]
        ],
        [
            'name' => '4',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Onde é realizado o contato entre a pessoa presa e o advogado?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '13',
            'translate' => [
                'pt-br' => 'A Defensoria Pública do Estado comparece com regularidade?'
            ],
            'type' => 'boolean',
            // Periodicidade
        ],
        [
            'name' => '5_1',
            'depends_on' => [
                'field' => '13_5',
                'value' => null
            ],
            'page' => '13',
            'translate' => [
                'pt-br' => 'Periodicidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Saídas temporárias'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Livramento condicional'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Progressões'
            ],
            'type' => 'text',
        ],
        [
            'name' => '9',
            'page' => '13',
            'translate' => [
                'pt-br' => 'Indulto'
            ],
            'type' => 'text',
        ],
        //END page 13

        //BEGIN page 14
        [
            'name' => '1',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Há oficinas de trabalho?'
            ],
            'type' => 'boolean',
            // Quantidade
        ],
        [
            'name' => '1_1',
            'depends_on' => [
                'field' => '14_1',
                'value' => null
            ],
            'page' => '14',
            'translate' => [
                'pt-br' => 'Quantidade'
            ],
            'type' => 'text',
        ],
        [
            'name' => '2',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Quantas das oficinas são administradas pelo estabelecimento?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Quantas das oficinas são administradas em parceria com a iniciativa privada?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Total de presos ou internos com permissão para trabalho externo'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Há avaliação das aptidões e capacidades do preso para sua alocação em determinado trabalho?'
            ],
            'type' => 'boolean',
            // Em caso positivo, como essa avaliação é realizada?
        ],
        [
            'name' => '6_1',
            'depends_on' => [
                'field' => '14_6',
                'value' => null
            ],
            'page' => '14',
            'translate' => [
                'pt-br' => 'Como essa avaliação é realizada?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '14',
            'translate' => [
                'pt-br' => 'Há avaliação e estímulo ao crescimento profissional que permita a qualificação ou diversificação do trabalho?'
            ],
            'type' => 'boolean',
            // Em caso positivo, descreva
        ],
        [
            'name' => '7_1',
            'depends_on' => [
                'field' => '14_7',
                'value' => null
            ],
            'page' => '14',
            'translate' => [
                'pt-br' => 'Descreva'
            ],
            'type' => 'text',
        ],
        //END page 14

        //BEGIN page 15
        [
            'name' => '2',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Alfabetização'
            ],
            'type' => 'text',
        ],
        [
            'name' => '3',
            'page' => '15',
            'translate' => [
                'pt-br' => 'ensino fundamental'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '15',
            'translate' => [
                'pt-br' => 'ensino médio'
            ],
            'type' => 'text',
        ],
        [
            'name' => '5',
            'page' => '15',
            'translate' => [
                'pt-br' => 'profissionalizante'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '15',
            'translate' => [
                'pt-br' => 'outros'
            ],
            'type' => 'text',
        ],
        [
            'name' => '7',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Especificar'
            ],
            'type' => 'text',
        ],
        [
            'name' => '8',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Os cursos são ministrados por'
            ],
            'type' => 'select',
            'options' => [
                'Professores do Sistema Penitenciário Estadual',
                'Professores da Secretaria Estadual de Educação',
                'Professores da Secretaria Municipal de Educação',
                'Presos monitores',
                'Voluntários',
                'Outros professores', // Especificar
            ]
        ],
        [
            'name' => '9',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Há atividades esportivas?'
            ],
            'type' => 'boolean',
            // Quais / Onde
        ],
        [
            'name' => '10',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Há atividades culturais/lazer?'
            ],
            'type' => 'boolean',
            // Quais / Onde
        ],
        [
            'name' => '11',
            'page' => '15',
            'translate' => [
                'pt-br' => 'Se há biblioteca, como funciona o acesso das pessoas presas aos livros'
            ],
            'type' => 'text',
        ],
        //END page 15

        //BEGIN page 16
        [
            'name' => '1',
            'page' => '16',
            'translate' => [
                'pt-br' => 'Há visita de religiosos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '16',
            'translate' => [
                'pt-br' => 'Quais denominações visitam o estabelecimento?'
            ],
            'type' => 'select',
            'options' => [
                'Espíritas',
                'Católicos',
                'Evangélicos',
                'de Matriz Africana',
                'Outra',
            ]
        ],
        [
            'name' => '3',
            'page' => '16',
            'translate' => [
                'pt-br' => 'Onde são realizadas as cerimônias religiosas?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '4',
            'page' => '16',
            'translate' => [
                'pt-br' => 'É permitida a entrada de objetos que fazem parte da cerimônia?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '16',
            'translate' => [
                'pt-br' => 'As necessidades religiosas são consideradas com relação às vestimentas, horários e rotinas?'
            ],
            'type' => 'boolean',
        ],
        //END page 16

        //BEGIN page 17
        [
            'name' => '1',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Há recintos adequados para a atividade de assistência social?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Contato com familiares'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '3',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Documentos'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '4',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Benefícios da Previdência Social'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Ações com os egressos'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Ações com o SUAS'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '17',
            'translate' => [
                'pt-br' => 'Projetos'
            ],
            'type' => 'boolean',
            //Quais
        ],
        //END page 17

        //BEGIN page 18
        [
            'name' => '1',
            'page' => '18',
            'translate' => [
                'pt-br' => 'A segurança interna é realizada por'
            ],
            'type' => 'select',
            'options' => [
                'Agentes penitenciários',
                'Policiais civis',
                'Policiais militares',
                'Terceirizados',
                'Outros',
            ]
        ],
        [
            'name' => '2',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Arma menos letal (bala de borracha)'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '3',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Arma letal'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '4',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Taser'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Gás de pimenta / lacrimogênio'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Cacetete / Tonfa'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Algemas'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Rádio'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '9',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Alarme'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Circuito de vigilância interna'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '11',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Outro'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Os usuários têm porte de armas?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica',
            ]
        ],
        [
            'name' => '13',
            'page' => '18',
            'translate' => [
                'pt-br' => 'É garantido treinamento periódico?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica',
            ]
        ],
        [
            'name' => '14',
            'page' => '18',
            'translate' => [
                'pt-br' => 'No caso de emprego de arma de fogo, é feito registro?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica',
            ]
        ],
        [
            'name' => '15',
            'page' => '18',
            'translate' => [
                'pt-br' => 'No caso de uso de arma tipo Taser os registros de descarga do equipamento são identificados por servidor?'
            ],
            'type' => 'select',
            'options' => [
                'Sim',
                'Não',
                'Não se aplica',
            ]
        ],
        [
            'name' => '16',
            'page' => '18',
            'translate' => [
                'pt-br' => 'A segurança externa é realizada por'
            ],
            'type' => 'select',
            'options' => [
                'policiais civis',
                'policiais militares',
                'agentes penitenciários',
                'terceiros',
                'outros',
            ]
        ],
        [
            'name' => '17',
            'page' => '18',
            'translate' => [
                'pt-br' => 'A escolta externa é realizada por'
            ],
            'type' => 'select',
            'options' => [
                'policiais civis',
                'policiais militares',
                'agentes penitenciários',
                'terceiros',
                'outros',
            ]
        ],
        [
            'name' => '18',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Há escolta externa especifica para área de saúde'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '19',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Existe grupo de intervenção especial vinculado à unidade?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '20',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Caso exista, quem são os envolvidos'
            ],
            'type' => 'select',
            'options' => [
                'policiais civis',
                'policiais militares',
                'agentes penitenciários',
                'terceiros',
                'outros',
            ]
        ],
        [
            'name' => '21',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Portal detector de metal'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '22',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Raquete detectora de metal'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '23',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Banco detector de metal'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '24',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Raio X'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '25',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Espectômetro'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '26',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Body Scanner'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '27',
            'page' => '18',
            'translate' => [
                'pt-br' => 'Outro'
            ],
            'type' => 'text',
        ],
        //END page 18

        //BEGIN page 19
        [
            'name' => '1',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Há registro de imposição de sanção disciplinar aos presos?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Qual a forma adotada para o registro?'
            ],
            'type' => 'select',
            'options' => [
                'Livro',
                'PAD',
                'Procedimento Eletrônico',
                'Outro',
            ]
        ],
        [
            'name' => '3',
            'page' => '19',
            'translate' => [
                'pt-br' => 'No registro da sanção de natureza grave é anotado o prévio procedimento disciplinar?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '4',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Há sanção disciplinar de natureza grave sem instauração do respectivo procedimento?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Toda notícia de falta disciplinar enseja a instauração de procedimento?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '19',
            'translate' => [
                'pt-br' => 'A falta disciplinar é reconhecida judicialmente?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '19',
            'translate' => [
                'pt-br' => 'São executadas sanções coletivas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '19',
            'translate' => [
                'pt-br' => 'É observado o direito de defesa do preso?'
            ],
            'type' => 'boolean',
            //Se sim, em qual fase?  fase administrativa  fase judicial
        ],
        [
            'name' => '9',
            'page' => '19',
            'translate' => [
                'pt-br' => 'O ato administrativo que determina a aplicação da sanção disciplinar é motivado?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Quais as condições da cela usada para aplicação de sanção disciplinar?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '11',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Qual o maior período aplicado de isolamento?'
            ],
            'type' => 'select',
            'options' => [
                '10 dias',
                '20 dias',
                '30 dias',
                'outro',
            ]
        ],
        [
            'name' => '12',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Qual o tempo médio de rebaixamento de comportamento ou reabilitação por falta grave?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '13',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Qual o número de sanções por falta grave (mês)?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '14',
            'page' => '19',
            'translate' => [
                'pt-br' => 'Houve motins ou rebeliões nos últimos 12 meses?'
            ],
            'type' => 'boolean',
        ],
        //END page 19

        //BEGIN page 20
        [
            'name' => '1',
            'page' => '20',
            'translate' => [
                'pt-br' => 'A visita social ocorre regularmente?'
            ],
            'type' => 'boolean',
            // frequencia
        ],
        [
            'name' => '2',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Quantas pessoas podem ser cadastradas por preso para realizarem a visita?'
            ],
            'type' => 'select',
            'options' => [
                '1 ou 2',
                '3 ou 4',
                '5 ou 6',
                '7 ou 8',
                '9 ou mais',
            ]
        ],
        [
            'name' => '3',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Quantas pessoas podem realizar a visita por vez?'
            ],
            'type' => 'select',
            'options' => [
                '1 ou 2',
                '3 ou 4',
                '5 ou 6',
                '7 ou 8',
                '9 ou mais',
            ]
        ],
        [
            'name' => '4',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Qual o local que ocorre a visita social'
            ],
            'type' => 'select',
            'options' => [
                'pátio de visita',
                'pátio do banho de sol',
                'celas',
                'outro',
            ]
        ],
        [
            'name' => '5',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Há local específico para visita de crianças?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '6',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Há permissão para visitas íntimas?'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '7',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Há permissão para visitas íntimas homoafetivas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '8',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Qual o local que ocorre a visita íntima?'
            ],
            'type' => 'select',
            'options' => [
                'módulo de visita íntima',
                'celas',
                'outro',
            ]
        ],
        [
            'name' => '9',
            'page' => '20',
            'translate' => [
                'pt-br' => 'Quais os procedimentos de revista dos visitantes?'
            ],
            'type' => 'select',
            'options' => [
                'mecânica(detector de metais, raquetes, banco, espectômetro)',
                'manual sem desnudamento',
                'com desnudamento',
                'outro',
            ]
        ],
        [
            'name' => '10',
            'page' => '20',
            'translate' => [
                'pt-br' => 'É permitida a visita de menores de 18 anos?'
            ],
            'type' => 'boolean',
        ],
        //END page 20

        //BEGIN page 21
        [
            'name' => '1',
            'page' => '21',
            'translate' => [
                'pt-br' => 'Há reclamações sobre quais aspectos'
            ],
            'type' => 'select',
            'options' => [
                'Instalações',
                'Assistência Jurídica',
                'Assistência Saúde',
                'Assistência Educacional',
                'Assistência social',
                'Atividades Esportivas',
                'Lazer',
                'Visita',
                'Maus tratos ou tortura',
                'Outros',
            ]
        ],
        [
            'name' => '2',
            'page' => '21',
            'translate' => [
                'pt-br' => 'No caso de maus tratos ou tortura, há indícios dos fatos relatados?'
            ],
            'type' => 'boolean',
            /**
             *  Ferimentos no corpo
             *  Marcas de projéteis nas celas ou outros ambientes
             *  Relatos idênticos em diferentes alas
             *  Nas datas dos eventos houve cancelamento de visita, entrada de grupos especiais de intervenção, transferência de presos, movimentações noturnas ou outra situação atípica
             *  Locais característicos como ambiente de castigo (sem colchão, sem sanitário, sem iluminação, sem ventilação, sujos, com insetos, entre outros aspectos)
             *  Uso de bala clava (capuz)
             *  Outros:  -->
             */
        ],
        [
            'name' => '3',
            'page' => '21',
            'translate' => [
                'pt-br' => 'Quais providências foram tomadas para apurar os fatos até o momento?'
            ],
            'type' => 'select',
            'options' => [
                'Exame de corpo de delito',
                'Denúncia formalizada ao Juiz ou Ministério Público',
                'Inquérito',
                'Instauração de procedimento administrativo',
                'Outro',
            ]
        ],
        [
            'name' => '4',
            'page' => '21',
            'translate' => [
                'pt-br' => 'Quais providências serão tomadas para apurar os fatos a partir de agora?'
            ],
            'type' => 'select',
            'options' => [
                'Exame de corpo de delito',
                'Denúncia formalizada ao Juiz ou Ministério Público',
                'Inquérito',
                'Instauração de procedimento administrativo',
                'Outro',
            ]
        ],
        [
            'name' => '5',
            'page' => '21',
            'translate' => [
                'pt-br' => 'Há orientação no estabelecimento quanto à forma de acessar (assinalar as opções em que houver orientação)'
            ],
            'type' => 'select',
            'options' => [
                'Ouvidoria',
                'Corregedoria',
                'Conselho da Comunidade',
                'Conselho Penitenciário',
                'Comissão de DH da OAB',
                'Disque 100',
                'Outro',
            ]
        ],
        [
            'name' => '6',
            'page' => '21',
            'translate' => [
                'pt-br' => 'Outras informações'
            ],
            'type' => 'text',
        ],
        //END page 21

        //BEGIN page 22
        [
            'name' => '1',
            'page' => '22',
            'translate' => [
                'pt-br' => 'No momento da inclusão da pessoa presa, há explicações sobre o funcionamento do estabelecimento?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '2',
            'page' => '22',
            'translate' => [
                'pt-br' => 'No momento da inclusão da pessoa presa, há explicações sobre direitos e deveres do preso?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '3',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Quando se aproxima a liberdade há algum trabalho realizado para preparação do preso?'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '4',
            'page' => '22',
            'translate' => [
                'pt-br' => 'É permitida a entrada de jornais e revistas?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '5',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Como funciona o envio e recebimento de correspondências?'
            ],
            'type' => 'text',
        ],
        [
            'name' => '6',
            'page' => '22',
            'translate' => [
                'pt-br' => 'As pessoas presas têm acesso a telefone público?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '7',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Há alistamento, transferência e revisão eleitoral de presos provisórios?'
            ],
            'type' => 'boolean',
            //           <!-- motivo -->
        ],
        [
            'name' => '8',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Rádio/Aparelho de Som'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '9',
            'page' => '22',
            'translate' => [
                'pt-br' => 'TV'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '10',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Vídeo/DVD'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '11',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Geladeira'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '12',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Fogão/Fogareiro/Mergulhão/Rabo Quente'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '13',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Outros'
            ],
            'type' => 'text',
        ],
        [
            'name' => '14',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Há organizações não governamentais atuando no estabelecimento?'
            ],
            'type' => 'boolean',
        ],
        [
            'name' => '15',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Se existe, em quais áreas'
            ],
            'type' => 'select',
            'options' => [
                'gestão',
                'educação',
                'saúde',
                'assistência social',
                'trabalho',
                'religiosa',
                'comunicação',
                'cidadania',
                'reciclagem',
                'manutenção',
                'outras',
                /**
                 * <!-- Qual a frequência:  diária             semanal
                 *  quinzenal        mensal
                 *  esporádico      outro: -->
                 */
            ]
        ],
        [
            'name' => '16',
            'page' => '22',
            'translate' => [
                'pt-br' => 'Como é tratado o lixo produzido no estabelecimento?'
            ],
            'type' => 'select',
            'options' => [
                'separado',
                'reciclado',
                'não é recolhido',
                'coleta municipal',
                'outro',
            ]
        ],
        //END page 22

        //BEGIN page 23
        [
            'name' => '1',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Juiz Corregedor'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '2',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Juiz de Execução'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '3',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Ministério Público'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '4',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Defensor Público'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '5',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Conselho Penitenciário'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '6',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Conselho da Comunidade'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '7',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Conselho Estadual de Direitos Humanos ou Comitê Estadual de Combate à Tortura'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '8',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Comissão de Direitos Humanos da OAB'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '9',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Pastoral Carcerária'
            ],
            'type' => 'boolean',
            //Frequencia
        ],
        [
            'name' => '10',
            'page' => '23',
            'translate' => [
                'pt-br' => 'Outros'
            ],
            'type' => 'text',
        ],
        //END page 23

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

        $fieldsDocuments = [];

        foreach (self::$fields as $field) {
            $formField = new FormField();

            $formField->setName(self::getFieldName($field));

            if (array_key_exists('options', $field)) {
                $formField->setOptions($field['options']);

                if (array_key_exists('other', $field['options'])) {
                    $formField->setFreeTextOption('other');
                }
            }

            if (array_key_exists('depends_on', $field)) {
                $dependsOn = $fieldsDocuments[$field['depends_on']['field']];
                $depends = new FormFieldDepends($dependsOn, $field['depends_on']['value']);
                $formField->addDependsOn($depends);
                unset($dependsOn);
                unset($depends);
            }

            $documentManager->persist($formField);
            $form->addField($formField);

            $fieldsDocuments[self::getFieldName($field)] = $formField;

            unset($formField);

            $translation = new Translation();
            $translation->setKey($fieldPrefix . '-field-' . self::getFieldName($field));
            /** @var Language $lang */
            $lang = $this->getReference(LoadLangs::$reference_prefix . 'pt-br');
            $translation->setLanguage($lang);
            $translation->setValue($field['translate']['pt-br']);
            $documentManager->persist($translation);

            unset($translation);
        }

        unset($fieldsDocuments);

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