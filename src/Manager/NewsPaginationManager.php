<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\Manager;

use Contao\Config;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\Pagination;
use HeimrichHannot\HeadBundle\Tag\Link\LinkCanonical;
use HeimrichHannot\HeadBundle\Tag\Link\LinkNext;
use HeimrichHannot\HeadBundle\Tag\Link\LinkPrev;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use HeimrichHannot\NewsPaginationBundle\Util\PaginationUtil;
use HeimrichHannot\RequestBundle\Component\HttpFoundation\Request;
use HeimrichHannot\UtilsBundle\Pagination\TextualPagination;
use HeimrichHannot\UtilsBundle\String\StringUtil;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class NewsPaginationManager
{
    public static $manualPaginationFound = false;

    public static $tags = [
        'p',
        'span',
        'strong',
        'i',
        'em',
        'div',
    ];
    /**
     * @var PaginationUtil
     */
    protected $paginationUtil;
    /**
     * @var LinkCanonical
     */
    private $linkCanonical;

    /**
     * @var LinkPrev
     */
    private $linkPrev;

    /**
     * @var LinkNext
     */
    private $linkNext;

    /**
     * @var UrlUtil
     */
    private $urlUtil;

    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var Request
     */
    private $request;

    public function __construct(
        LinkCanonical $linkCanonical,
        LinkPrev $linkPrev,
        LinkNext $linkNext,
        UrlUtil $urlUtil,
        StringUtil $stringUtil,
        Request $request,
        PaginationUtil $paginationUtil
    ) {
        $this->linkCanonical = $linkCanonical;
        $this->linkPrev = $linkPrev;
        $this->linkNext = $linkNext;
        $this->urlUtil = $urlUtil;
        $this->stringUtil = $stringUtil;
        $this->request = $request;
        $this->paginationUtil = $paginationUtil;
    }

    /**
     * @param $output mixed Can be instance of Template or Item
     * @param $config
     */
    public function addNewsPagination($output, array $news, $config)
    {
        $output->module = $config;

        if (!$this->linkCanonical->hasContent()) {
            $this->linkCanonical->setContent($this->urlUtil->getCurrentUrl([]));
        }

        // no pagination if full version parameter is set
        if ($config->fullVersionGetParameter && $this->request->getGet($config->fullVersionGetParameter)
            || $config->acceptPrintGetParameter && ($this->request->getGet('print') || $this->request->getGet('rp'))) {
            return;
        }

        switch ($config->paginationMode) {
            case NewsPaginationBundle::MODE_AUTO:
                $this->doAddNewsPagination($output, $news, $config);

                break;

            case NewsPaginationBundle::MODE_MANUAL:
                $this->doAddManualNewsPagination($output, $news, $config);

                break;

            case NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK:
                $this->doAddManualNewsPagination($output, $news, $config);

                if (!static::$manualPaginationFound) {
                    $this->doAddNewsPagination($output, $news, $config);
                }

                break;
        }
    }

    public function doAddManualNewsPagination($output, array $news, $config)
    {
        $pageParam = 'page_n'.$config->id;
        $page = $this->request->getGet($pageParam) ?: 1;
        $pageCount = 0;
        $teaserData = [];

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $node = new HtmlPageCrawler('<div><div class="news-pagination-content">'.\Contao\StringUtil::restoreBasicEntities($output->text).'</div></div>');
        $startElements = $node->filter('.news-pagination-content > [class*="ce_news_pagination_start"]');

        if ($startElements->count() < 1) {
            return;
        }

        static::$manualPaginationFound = true;

        $startElements->each(function ($element) use ($page, &$pageCount, &$teaserData) {
            $intIndex = $element->getAttribute('data-index');

            if (method_exists($element, 'getCombinedText')) {
                $text = $element->getCombinedText();
            } else {
                $text = $element->text();
            }

            $teaserData[$intIndex] = [
                [
                    'text' => $element->getAttribute('data-pagination-title') ?: trim($text),
                ],
            ];

            if ($intIndex > $pageCount) {
                $pageCount = $intIndex;
            }

            if ($page != $intIndex) {
                $element->remove();
            }
        });

        $output->text = str_replace(['%7B', '%7D'], ['{', '}'], $node->saveHTML());

        // path without query string
        $path = \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getPathInfo();
        $url = Environment::get('url').$path;

        // add pagination
        $singlePageUrl = $this->urlUtil->addQueryString($config->fullVersionGetParameter.'=1', $url);
        $this->addPagination($output, $teaserData, $config, $pageCount, $pageParam, $singlePageUrl);

        $this->handleMetaTags($pageCount, $page, $pageParam, $config, $news['alias'], $url);
    }

    public function doAddNewsPagination($output, array $news, $config)
    {
        $pageParam = 'page_n'.$config->id;
        $currentPage = is_numeric($this->request->getGet($pageParam)) && $this->request->getGet($pageParam) > 0 ? $this->request->getGet($pageParam) : 1;

        $options = ['avoidTrailingHeadlines' => (bool) $config->avoidTrailingHeadlines];

        if ($config->paginationCeTextCssSelector) {
            $options['selector'] = $config->paginationCeTextCssSelector;
        }

        $result = $this->paginationUtil->paginateHtmlText($output->text, $config->paginationMaxCharCount, $currentPage, $options);

        $output->text = $result->getText();

        /* @var $objPage \Contao\PageModel */
        global $objPage;

        // path without query string
        $path = \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getPathInfo();
        $url = Environment::get('url').$path;

        // if path is id, take absolute url from current page
        if (is_numeric(ltrim($path, '/'))) {
            $url = $objPage->getAbsoluteUrl();
        }

        // add pagination
        $singlePageUrl = $this->urlUtil->addQueryString($config->fullVersionGetParameter.'=1', $url);
        $this->addPagination($output, $result->getPages(), $config, $result->getPageCount(), $pageParam, $singlePageUrl);

        // add head info
        $this->handleMetaTags($result->getPageCount(), $currentPage, $pageParam, $config, $news['alias'], $url);
    }

    public function handleMetaTags(int $pageCount, int $currentPage, string $pageParam, $config, string $alias, string $url)
    {
        if ($pageCount > 1) {
            $canonical = $this->linkCanonical->getContent();

            // canonical link must contain the current news url or not set
            if ($config->addFullVersionCanonicalLink && $config->fullVersionGetParameter) {
                if ($canonical && false !== strpos($canonical, urlencode($alias))) {
                    $url = $canonical;
                }

                $this->linkCanonical->setContent($this->urlUtil->addQueryString($config->fullVersionGetParameter.'=1', $url));
            }

            // prev and next links
            if ($config->addPrevNextLinks) {
                if (1 == $currentPage) {
                    $this->linkPrev->setContent('');

                    $this->linkNext->setContent($this->urlUtil->addQueryString($pageParam.'=2', $url));
                } elseif ($currentPage > 1 && $currentPage < $pageCount) {
                    $this->linkPrev->setContent($this->urlUtil->addQueryString($pageParam.'='.($currentPage - 1), $url));

                    $this->linkNext->setContent($this->urlUtil->addQueryString($pageParam.'='.($currentPage + 1), $url));
                } else {
                    if ($currentPage >= $pageCount) {
                        $this->linkPrev->setContent($this->urlUtil->addQueryString($pageParam.'='.($pageCount - 1), $url));

                        $this->linkNext->setContent('');
                    }
                }
            }
        }
    }

    protected function addPagination($output, array $teaserData, $config, int $pageCount, string $pageParam, string $singlePageUrl)
    {
        $paginationTemplate = null;

        if ('' !== $config->templatePagination) {
            $paginationTemplate = new FrontendTemplate($config->templatePagination);
            $paginationTemplate->singlePageUrl = $singlePageUrl;
            $paginationTemplate->singlePageLabel = $GLOBALS['TL_LANG']['MSC']['readOnSinglePage'];
        }

        // normal pagination
        $pagination = new Pagination($pageCount, 1, Config::get('maxPaginationLinks'), $pageParam, $paginationTemplate);

        $output->newsPagination = $pagination->generate("\n  ");

        // textual pagination
        if ($config->addTextualPagination) {
            $teasers = $this->createPageTeasers($teaserData, $config);

            // always show all truncated teasers
            $pagination = new TextualPagination($teasers, $singlePageUrl, $pageCount, 1, 999, $pageParam);

            $output->textualPagination = $pagination->generate("\n  ");
        }
    }

    protected function createPageTeasers(array $splitted, $config): array
    {
        // create teasers for textual pagination
        $teasers = [];

        foreach ($splitted as $page => $data) {
            $teaserParts = [];
            $textLength = 0;
            $maxCountExceeded = false;

            foreach ($data as $i => $element) {
                $elementLength = \strlen($element['text']);

                if ($elementLength > $config->textPaginationMaxCharCount) {
                    $truncated = $this->stringUtil->truncateHtml($element['text'], $config->textPaginationMaxCharCount, '');

                    if (\strlen($truncated) + $textLength > $config->textPaginationMaxCharCount) {
                        $maxCountExceeded = true;

                        continue;
                    }

                    if ($i === \count($data) - 1) {
                        $maxCountExceeded = true;
                    }

                    $teaserParts[] = $truncated;
                    $textLength += \strlen($truncated);
                } else {
                    if ($elementLength + $textLength > $config->textPaginationMaxCharCount) {
                        continue;
                    }

                    $teaserParts[] = $element['text'];
                    $textLength += $elementLength;
                }
            }

            $teasers[$page] = implode(' ', $teaserParts).($maxCountExceeded ? $config->textPaginationDelimiter : '');
        }

        return $teasers;
    }
}
