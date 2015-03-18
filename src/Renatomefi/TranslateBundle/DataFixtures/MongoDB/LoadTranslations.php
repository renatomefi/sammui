<?php

namespace Renatomefi\TranslateBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\TranslateBundle\Document\Translation;

/**
 * Class LoadTranslations
 * @package Renatomefi\TranslateBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadTranslations extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @var array Translations to apply into languages
     */
    protected $_newTranslations = [
        'index-title-sidebar-left' => 'Menu',
        'index-title-sidebar-right' => 'Admin',
        'sidebar-menu-languages' => [
            'en-us' => 'Choose a Lang',
            'pt-br' => 'Escolha uma Língua'],
        'sidebar-menu-form' => [
            'en-us' => 'Fill a Form',
            'pt-br' => 'Formulários'],
        'login-index' => 'Login',
        'login-form-legend' => [
            'en-us' => 'Enter a valid Symfony 2 User',
            'pt-br' => 'Utilize um usuário válido do Symfony 2'],
        'login-form-username' => 'Username',
        'login-form-password' => 'Password',
        'login-logout-force' => [
            'en-us' => 'Having trouble? Force your logout and clean your session',
            'pt-br' => 'Problemas? Limpe sua sessão e acessos']
    ];

    /**
     * @param $key
     * @param $value
     * @param $reference
     * @return Translation
     */
    protected function createTranslateObj($key, $value, $reference)
    {
        $translation = new Translation();

        $translation->setLanguage($reference);
        $translation->setKey($key);
        $translation->setValue($value);

        return $translation;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        foreach ($this->_newTranslations as $k => $v) {
            foreach (LoadLangs::$default_langs as $lang) {
                $manager->persist(
                    $this->createTranslateObj(
                        $k,
                        (is_array($v) ? $v[$lang] : $v),
                        $this->getReference(LoadLangs::$reference_prefix . $lang)
                    )
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