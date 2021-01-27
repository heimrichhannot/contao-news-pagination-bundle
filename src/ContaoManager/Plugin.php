<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use HeimrichHannot\HeadBundle\HeimrichHannotContaoHeadBundle;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        $bundles = [ContaoNewsBundle::class, HeimrichHannotContaoHeadBundle::class];

        if (class_exists('HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle')) {
            $bundles[] = \HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle::class;
        }

        if (class_exists('HeimrichHannot\NewsBundle\HeimrichHannotContaoNewsBundle')) {
            $bundles[] = \HeimrichHannot\NewsBundle\HeimrichHannotContaoNewsBundle::class;
        }

        if (class_exists('Hofff\Contao\ContentNavigation\HofffContentNavigationBundle')) {
            $bundles[] = \Hofff\Contao\ContentNavigation\HofffContentNavigationBundle::class;
        }

        return [
            BundleConfig::create(NewsPaginationBundle::class)
                ->setLoadAfter($bundles),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@NewsPaginationBundle/Resources/config/services.yml');
    }
}
