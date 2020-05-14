<?php

namespace HeimrichHannot\NewsPaginationBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use HeimrichHannot\HeadBundle\HeimrichHannotContaoHeadBundle;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface
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

