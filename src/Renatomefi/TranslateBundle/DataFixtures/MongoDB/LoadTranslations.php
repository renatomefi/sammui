<?php

namespace Renatomefi\TranslateBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\TranslateBundle\Document\Translation;

/**
 * Class LoadTranslations
 * @package Renatomefi\TranslateBundle\DataFixtures\MongoDB
 */
class LoadTranslations extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @var array Translations to apply into languages
     */
    protected $_translations = [
        'en-us' => [
            'index-title-sidebar-left' => 'Menu',
            'index-title-sidebar-right' => 'Admin',
            'sidebar-menu-languages' => 'Choose a Lang',
            'sidebar-menu-form' => 'Fill a Form',
            'login-index' => 'Login',
            'login-form-legend' => 'Enter a valid Symfony 2 User',
            'login-form-username' => 'Username',
            'login-form-password' => 'Password',
            'login-logout-force' => 'Having trouble? Force your logout and clean your session'
        ],
        'pt-br' => [
            'index-title-sidebar-left' => 'Menu',
            'index-title-sidebar-right' => 'Admin',
            'sidebar-menu-languages' => 'Escolha uma Língua',
            'sidebar-menu-form' => 'Formulários',
            'login-index' => 'Login',
            'login-form-legend' => 'Utilize um usuário válido do Symfony 2',
            'login-form-username' => 'Username',
            'login-form-password' => 'Password',
            'login-logout-force' => 'Problemas? Limpe sua sessão e acessos'
        ]
    ];

    /**
     * @param $key
     * @param $value
     * @param $reference
     * @return Translation
     */
    protected function createTranslateObj($key, $value, $reference)
    {
        $t = new Translation();

        $t->setLanguage($reference);
        $t->setKey($key);
        $t->setValue($value);

        return $t;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->_translations as $lang => $t) {
            foreach ($t as $k => $v) {
                $manager->persist(
                    $this->createTranslateObj($k, $v, $this->getReference(LoadLangs::$reference_prefix . $lang))
                );
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }

}