<?php

namespace Renatomefi\TranslateBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Renatomefi\TranslateBundle\Document\Language;

class LoadLangs extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lang = new Language();

        $lang->setKey('pt-br');

        $manager->persist($lang);

        unset($lang);

        $lang = new Language();

        $lang->setKey('en-us');

        $manager->persist($lang);
        $manager->flush();

        $this->addReference('langs-default', $lang);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}