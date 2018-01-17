<?php

namespace HeimrichHannot\NewsPaginationBundle\EventListener;


use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use HeimrichHannot\Haste\Util\Container;
use HeimrichHannot\Haste\Util\Url;
use Symfony\Component\HttpFoundation\Request;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use Contao\StringUtil;

class HookListener extends \Controller
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * Constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    static $manualPaginationFound = false;

    static $arrTags = [
        'p',
        'span',
        'strong',
        'i',
        'em',
        'div'
    ];

    public function addNewsPagination($objTemplate, $arrArticle, $objModule)
    {
        $objTemplate->module = $objModule;

        if ($objModule->addManualPagination) {
            $this->doAddManualNewsPagination($objTemplate, $arrArticle, $objModule);
        }

        if (!static::$manualPaginationFound && $objModule->addPagination) {
            $this->doAddNewsPagination($objTemplate, $arrArticle, $objModule);
        }
    }

    public function doAddManualNewsPagination($objTemplate, $arrArticle, $objModule)
    {
        $intPage     = \Input::get('page_n' . $objModule->id) ?: 1;
        $intMaxIndex = 0;

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $objNode          = new HtmlPageCrawler('<div><div class="news-pagination-content">' . StringUtil::restoreBasicEntities($objTemplate->text) . '</div></div>');
        $objStartElements = $objNode->filter('.news-pagination-content > [class*="ce_news_pagination_start"]');

        if ($objStartElements->count() < 1) {
            return;
        }

        static::$manualPaginationFound = true;

        $objStartElements->each(
            function ($objElement) use ($intPage, &$intMaxIndex) {
                $intIndex = $objElement->getAttribute('data-index');

                if ($intIndex > $intMaxIndex) {
                    $intMaxIndex = $intIndex;
                }

                if ($intPage != $intIndex) {
                    $objElement->remove();
                }
            }
        );

        $objTemplate->text = str_replace(['%7B', '%7D'], ['{', '}'], $objNode->saveHTML());

        // add pagination
        $objPagination               =
            new \Pagination($intMaxIndex, 1, \Config::get('maxPaginationLinks'), 'page_n' . $objModule->id);
        $objTemplate->newsPagination = $objPagination->generate("\n  ");
    }

    public function doAddNewsPagination($objTemplate, $arrArticle, $objModule)
    {
        $strPageParam   = 'page_n' . $objModule->id;
        $intCurrentPage = is_numeric(Container::getGet($strPageParam)) && Container::getGet($strPageParam) > 0 ?
            Container::getGet($strPageParam) : 1;

        // no pagination if full version parameter is set
        if ($objModule->fullVersionGetParameter && Container::getGet($objModule->fullVersionGetParameter)) {
            return;
        }

        $intMaxAmount = $objModule->paginationMaxCharCount;
        $intPageCount = 1;

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $objNode              = new HtmlPageCrawler('<div><div class="news-pagination-content">' . StringUtil::restoreBasicEntities($objTemplate->text) . '</div></div>');
        $intTextAmount        = 0;
        $strCeTextCssSelector = $objModule->paginationCeTextCssSelector;
        $arrTags              = static::$arrTags;

        // replace multiple br elements to
        $objNode->filter('.news-pagination-content > [class*="ce_"]')->each(
            function ($objElement) use (&$intTextAmount, $intMaxAmount, $arrTags, $objNode, $strCeTextCssSelector) {
                if (strpos($objElement->getAttribute('class'), 'ce_text') !== false && strpos($objElement->html(), 'figure') === false) {
                    $objElement->children($strCeTextCssSelector . ', figure')->each(
                        function ($objParagraph) use (&$intTextAmount, $intMaxAmount, $arrTags) {
                            $objParagraph->html(preg_replace('@<br\s?\/?><br\s?\/?>@i', '</p><p>', $objParagraph->html()));
                        });
                }
            }
        );

        // pagination
        $objNode     = new HtmlPageCrawler($objNode->saveHTML());
        $arrElements = [];

        // get relevant elements
        $objNode->filter('.news-pagination-content > [class*="ce_"]')->each(
            function ($objElement) use (&$intTextAmount, $intMaxAmount, $arrTags, $objNode, $strCeTextCssSelector, &$intPageCount, &$arrElements) {
                if (strpos($objElement->getAttribute('class'), 'ce_text') !== false &&
                    strpos($objElement->html(), 'figure') === false
                ) {
                    if ($strCeTextCssSelector) {
                        $objElement = $objElement->filter($strCeTextCssSelector);
                    }

                    $objElement->children()->each(
                        function ($objElement) use (&$intTextAmount, $intMaxAmount, $arrTags, &$intPageCount, &$arrElements) {
                            $arrElements[] = [
                                'element' => $objElement,
                                'text'    => $objElement->text(),
                                'tag'     => $objElement->nodeName(),
                                'length'  => strlen($objElement->text())
                            ];
                        }
                    );
                } else {
                    $arrElements[] = [
                        'element' => $objElement,
                        'text'    => $objElement->text(),
                        'tag'     => $objElement->nodeName(),
                        'length'  => 0
                    ];
                }
            }
        );

        // split array by text amounts
        $arrSplitted = [];

        foreach ($arrElements as $arrElement) {
            $intTextAmountOrigin = $intTextAmount;
            $intTextAmount       += $arrElement['length'];

            if ($intTextAmount > $intMaxAmount && $intTextAmountOrigin != 0) {
                $intPageCount++;
                $intTextAmount = $arrElement['length'];
            }

            if (!isset($arrSplitted[$intPageCount])) {
                $arrSplitted[$intPageCount] = [];
            }

            $arrSplitted[$intPageCount][] = $arrElement;
        }

        // hold together headlines and paragraphs
        if ($objModule->avoidTrailingHeadlines) {
            $arrResult = [];

            foreach ($arrSplitted as $intPage => $arrParts) {
                $arrHeadlines         = [];
                $arrNonHeadlines      = [];
                $blnTrailingHeadlines = true;

                for ($i = count($arrParts) - 1; $i > -1; $i--) {
                    if ($blnTrailingHeadlines && in_array($arrParts[$i]['tag'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'h7', 'h8', 'h9', 'h10'])) {
                        $arrHeadlines[] = $arrParts[$i];
                    } else {
                        // break overlap handling if headline is not trailing
                        $blnTrailingHeadlines = false;
                        $arrNonHeadlines[]    = $arrParts[$i];
                    }
                }

                if (empty($arrResult[$intPage])) {
                    $arrResult[$intPage] = array_reverse($arrNonHeadlines);
                } else {
                    $arrResult[$intPage] = array_merge($arrResult[$intPage], array_reverse($arrNonHeadlines));
                }

                if (!empty($arrHeadlines)) {
                    if (empty($arrResult[$intPage + 1])) {
                        $arrResult[$intPage + 1] = array_reverse($arrHeadlines);
                    } else {
                        $arrResult[$intPage + 1] = array_merge(array_reverse($arrHeadlines), $arrResult[$intPage + 1]);
                    }
                }
            }
        } else {
            $arrResult = $arrSplitted;
        }

        // can't be ;-)
        if ($intCurrentPage > $intPageCount) {
            $intCurrentPage = $intPageCount;
        }

        foreach ($arrResult as $intPage => $arrParts) {
            foreach ($arrParts as $arrPart) {
                if ($intCurrentPage) {
                    if ($intPage != $intCurrentPage) {
                        $arrPart['element']->remove();
                    }
                } else {
                    if ($intPage != 1) {
                        $arrPart['element']->remove();
                    }
                }
            }
        }

        $objTemplate->text = str_replace(['%7B', '%7D'], ['{', '}'], $objNode->saveHTML());

        // add pagination
        $objPagination               =
            new \Pagination($intPageCount, 1, \Config::get('maxPaginationLinks'), $strPageParam);
        $objTemplate->newsPagination = $objPagination->generate("\n  ");

        // add head info

        /** @var $objPage \Contao\PageModel */
        global $objPage;

        // path without query string
        $path = Request::createFromGlobals()->getPathInfo();
        $url  = \Contao\Environment::get('url') . $path;

        // if path is id, take absolute url from current page
        if (is_numeric(ltrim($path, '/'))) {
            $url = $objPage->getAbsoluteUrl();
        }

        if ($intPageCount > 1) {
            $canonical = \System::getContainer()->get('huh.head.tag.link_canonical')->getContent();

            // canonical link must contain the current news url or not set
            if ($objModule->addFullVersionCanonicalLink && $objModule->fullVersionGetParameter && (!$canonical || strpos($canonical, $arrArticle['alias']) !== false)) {
                \System::getContainer()->get('huh.head.tag.link_canonical')->setContent(
                    Url::addQueryString($objModule->fullVersionGetParameter . '=1', $url)
                );
            }

            // prev and next links
            if ($objModule->addPrevNextLinks) {
                if ($intCurrentPage == 1) {
                    \System::getContainer()->get('huh.head.tag.link_next')->setContent(
                        Url::addQueryString($strPageParam . '=2', $url)
                    );
                } elseif ($intCurrentPage > 1 && $intCurrentPage < $intPageCount) {
                    \System::getContainer()->get('huh.head.tag.link_prev')->setContent(
                        Url::addQueryString($strPageParam . '=' . ($intCurrentPage - 1), $url)
                    );

                    \System::getContainer()->get('huh.head.tag.link_next')->setContent(
                        Url::addQueryString($strPageParam . '=' . ($intCurrentPage + 1), $url)
                    );
                } else if ($intCurrentPage >= $intPageCount) {
                    \System::getContainer()->get('huh.head.tag.link_prev')->setContent(
                        Url::addQueryString($strPageParam . '=' . ($intPageCount - 1), $url)
                    );
                }
            }
        }
    }
}

