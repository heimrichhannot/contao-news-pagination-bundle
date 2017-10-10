<?php

namespace HeimrichHannot\NewsPaginationBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        $bundles = [ContaoNewsBundle::class];

        if (class_exists('HeimrichHannot\NewsBundle\HeimrichHannotContaoNewsBundle'))
        {
            $bundles[] = \HeimrichHannot\NewsBundle\HeimrichHannotContaoNewsBundle::class;
        }

        return [
            BundleConfig::create(NewsPaginationBundle::class)
                ->setLoadAfter($bundles)
        ];
    }
}

