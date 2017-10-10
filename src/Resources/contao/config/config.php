<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseArticles']['addNewsPagination'] = ['huh.news_pagination.listener.hooks', 'addNewsPagination'];

/**
 * Content elements
 */
if (\Input::get('do') == 'news' && \Input::get('table') == 'tl_content' || TL_MODE == 'FE') {
    $GLOBALS['TL_CTE']['news_pagination'] = [
        \HeimrichHannot\NewsPaginationBundle\ContaoNewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START =>
            'HeimrichHannot\NewsPagination\NewsPaginationStart',
        \HeimrichHannot\NewsPaginationBundle\ContaoNewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP  =>
            'HeimrichHannot\NewsPagination\NewsPaginationStop'
    ];
}

/**
 * Wrapper
 */
$GLOBALS['TL_WRAPPERS']['start'][] = \HeimrichHannot\NewsPaginationBundle\ContaoNewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START;
$GLOBALS['TL_WRAPPERS']['stop'][]  = \HeimrichHannot\NewsPaginationBundle\ContaoNewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;