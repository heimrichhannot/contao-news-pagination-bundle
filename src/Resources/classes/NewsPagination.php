<?php

namespace HeimrichHannot\NewsPagination;


use HeimrichHannot\Request\Request;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class NewsPagination extends \Controller
{
    const CONTENT_ELEMENT_NEWS_PAGINATION_START = 'news_pagination_start';
    const CONTENT_ELEMENT_NEWS_PAGINATION_STOP  = 'news_pagination_stop';
}