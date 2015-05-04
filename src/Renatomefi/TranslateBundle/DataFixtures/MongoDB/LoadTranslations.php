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
     * If you want a new Lang, look into LoadLangs references, or else it will crash!
     * @var array Translations to apply into languages
     */
    protected $_newTranslations = [
        // Title
        'index-title-sidebar-left' => 'Menu',
        'index-title-sidebar-right' => 'Admin',
        // Sidebar Left
        'sidebar-menu-languages' => [
            'en-us' => 'Choose a Lang',
            'pt-br' => 'Escolha uma Língua'],
        'sidebar-menu-form' => [
            'en-us' => 'Fill a Form',
            'pt-br' => 'Formulários'],
        // Page: Login
        'login-index' => 'Login',
        'login-form-legend' => [
            'en-us' => 'Enter a valid Symfony 2 User',
            'pt-br' => 'Utilize um usuário válido do Symfony 2'],
        'login-form-username' => 'Username',
        'login-form-password' => 'Password',
        'login-logout-force' => [
            'en-us' => 'Having trouble? Force your logout and clear your session',
            'pt-br' => 'Problemas? Limpe sua sessão e acessos'],
        // Page: Forms
        'form-start-title' => [
            'en-us' => 'Forms',
            'pt-br' => 'Formulários'],
        'form-start-choose' => [
            'en-us' => 'Form filling',
            'pt-br' => 'Preenchimento de formulários'],
        'form-select-default' => [
            'en-us' => 'Choose a form',
            'pt-br' => 'Escolha um formulário'],
        'form-no-forms' => [
            'en-us' => 'No forms found',
            'pt-br' => 'Nenhum formulário disponível'],
        'form-filling-page-index' => [
            'en-us' => 'Index',
            'pt-br' => 'Índice'],
        'form-filling-page-users' => [
            'en-us' => 'Users',
            'pt-br' => 'Usuários'],
        'form-filling-page-comments' => [
            'en-us' => 'Obs',
            'pt-br' => 'Comentários'],
        'form-filling-page-conclusion' => [
            'en-us' => 'Conclusion',
            'pt-br' => 'Conclusão'],
        'form-filling-page-upload' => [
            'en-us' => 'Files',
            'pt-br' => 'Arquivos'],
        'form-filling-page-form' => [
            'en-us' => 'Form',
            'pt-br' => 'Formulário'],
        'form-filling-index-pages-title' => [
            'en-us' => 'Pages Index',
            'pt-br' => 'Índice de Páginas'],
        // Form: Buttons 'form-filling-btn-save'
        'form-filling-btn-save' => [
            'en-us' => 'Save',
            'pt-br' => 'Salvar'],
        'form-filling-btn-readonly' => [
            'en-us' => 'Read Only mode',
            'pt-br' => 'Modo leitura'],
        'form-filling-btn-publish' => [
            'en-us' => 'Publish',
            'pt-br' => 'Publicar'],
        // Form: demo
        'sammui-form-demo' => [
            'en-us' => 'Demo Form',
            'pt-br' => 'Formulário de demonstração']
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