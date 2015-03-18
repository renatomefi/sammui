<?php

namespace Renatomefi\TranslateBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\TranslateBundle\Document\Language;

/**
 * Class LoadLangs
 * @package Renatomefi\TranslateBundle\DataFixtures\MongoDB
 * @codeCoverageIgnore
 */
class LoadLangs extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @var string
     */
    public static $reference_prefix = 'langs-';

    /**
     * @var array
     */
    public static $default_langs = ['en-us', 'pt-br'];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        foreach (static::$default_langs as $lang) {
            $language = new Language();
            $language->setKey($lang);
            $language->setLastUpdate(new \MongoDate());
            $manager->persist($language);
            $this->addReference(static::$reference_prefix . $lang, $language);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

}