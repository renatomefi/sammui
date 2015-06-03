<?php

namespace Renatomefi\TranslateBundle\Twig;

use Renatomefi\TranslateBundle\Document\Language;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SammuiTranslation extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Language
     */
    protected $language;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sammuiTranslate', array($this, 'translateFilter')),
        );
    }

    /**
     * Look for the translate at Language Document in order to translate from DB registers
     *
     * @param $value
     * @param $lang
     * @return string
     */
    public function translateFilter($value, $lang)
    {
        if (!$this->language) {
            $langDM = $this->container->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
            $this->language = $langDM->findOneByKey($lang);
        }

        return ($trans = $this->language->findTranslationByKey($value)) ? $trans->getValue() : $value;
    }

    public function getName()
    {
        return 'sammui_translate';
    }
}