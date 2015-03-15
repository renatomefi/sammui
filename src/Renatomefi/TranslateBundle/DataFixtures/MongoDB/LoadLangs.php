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
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // pt-br
        $langPt = new Language();
        $langPt->setKey('pt-br');
        $langPt->setLastUpdate(new \MongoDate());
        $this->addReference(static::$reference_prefix . 'pt-br', $langPt);

        // en-us
        $langEn = new Language();
        $langEn->setKey('en-us');
        $langEn->setLastUpdate(new \MongoDate());
        $this->addReference(static::$reference_prefix . 'en-us', $langEn);

        $manager->persist($langPt);
        $manager->persist($langEn);
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