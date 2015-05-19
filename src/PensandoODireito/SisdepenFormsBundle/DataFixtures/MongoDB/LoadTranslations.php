<?php

namespace PensandoODireito\SisdepenFormsBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\TranslateBundle\DataFixtures\MongoDB\LoadLangs;
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
        'form-inspecao-estabelecimentos-penais-group-anual' => [
            'en-us' => 'Anual',
            'pt-br' => 'Anual'],
        'form-inspecao-estabelecimentos-penais-group-semestral' => [
            'en-us' => 'Semester',
            'pt-br' => 'Semestral'],
        'form-inspecao-estabelecimentos-penais-group-mensal' => [
            'en-us' => 'Monthly',
            'pt-br' => 'Mensal']

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