<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\NewsPaginationBundle;

use HeimrichHannot\NewsPaginationBundle\DependencyInjection\NewsPaginationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NewsPaginationBundle extends Bundle
{
    const CONTENT_ELEMENT_NEWS_PAGINATION_START = 'news_pagination_start';
    const CONTENT_ELEMENT_NEWS_PAGINATION_STOP  = 'news_pagination_stop';

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new NewsPaginationExtension();
    }
}
