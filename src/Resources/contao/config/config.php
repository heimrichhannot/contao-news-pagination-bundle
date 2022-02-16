<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_HOOKS']['parseArticles']['addNewsPagination'] = [\HeimrichHannot\NewsPaginationBundle\EventListener\ParseArticlesListener::class, '__invoke'];
$GLOBALS['TL_HOOKS']['parseTemplate']['adjustHofffContentNavigation'] = [\HeimrichHannot\NewsPaginationBundle\EventListener\ParseTemplateListener::class, '__invoke'];

/*
 * Content elements
 */
if (\Input::get('do') && 'tl_content' == \Input::get('table') || TL_MODE == 'FE' || 'group' === \Contao\Input::get('do')) {
    $GLOBALS['TL_CTE']['news_pagination'] = [
        \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START => 'HeimrichHannot\NewsPaginationBundle\Element\NewsPaginationStart',
        \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP => 'HeimrichHannot\NewsPaginationBundle\Element\NewsPaginationStop',
    ];
}

/*
 * Wrapper
 */
$GLOBALS['TL_WRAPPERS']['start'][] = \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START;
$GLOBALS['TL_WRAPPERS']['stop'][] = \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;
