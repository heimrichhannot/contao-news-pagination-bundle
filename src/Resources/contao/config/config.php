<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseArticles']['addNewsPagination'] = ['huh.news_pagination.listener.hooks', 'addNewsPagination'];

/**
 * Content elements
 */
if (\Input::get('do') == 'news' && \Input::get('table') == 'tl_content' || TL_MODE == 'FE') {
    \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START =>
            'HeimrichHannot\NewsPaginationBundle\Element\NewsPaginationStart',
        \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP  =>
            'HeimrichHannot\NewsPaginationBundle\Element\NewsPaginationStop'
}

/**
 * Wrapper
 */
$GLOBALS['TL_WRAPPERS']['start'][] = \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START;
$GLOBALS['TL_WRAPPERS']['stop'][]  = \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;
