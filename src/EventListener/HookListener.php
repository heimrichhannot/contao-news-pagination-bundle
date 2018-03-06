<?php

namespace HeimrichHannot\NewsPaginationBundle\EventListener;


use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Environment;
use Contao\Module;
use Contao\Pagination;
use Contao\Template;
use HeimrichHannot\HeadBundle\Tag\Link\LinkCanonical;
use HeimrichHannot\HeadBundle\Tag\Link\LinkNext;
use HeimrichHannot\HeadBundle\Tag\Link\LinkPrev;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use HeimrichHannot\Request\Request;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use Contao\StringUtil;

class HookListener extends \Controller
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

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

    public function __construct(
        ContaoFrameworkInterface $framework,
        LinkCanonical $linkCanonical,
        LinkPrev $linkPrev,
        LinkNext $linkNext,
        UrlUtil $urlUtil
    ) {
        $this->framework     = $framework;
        $this->linkCanonical = $linkCanonical;
        $this->linkPrev      = $linkPrev;
        $this->linkNext      = $linkNext;
        $this->urlUtil       = $urlUtil;
    }

    static $manualPaginationFound = false;

    static $tags = [
        'p',
        'span',
        'strong',
        'i',
        'em',
        'div'
    ];

    public function addNewsPagination(Template $template, array $article, Module $module)
    {
        $template->module = $module;

        // no pagination if full version parameter is set
        if ($module->fullVersionGetParameter && Request::getGet($module->fullVersionGetParameter) ||
            $module->acceptPrintGetParameter && Request::getGet('print')
        ) {
            return;
        }

        switch ($module->paginationMode)
        {
            case NewsPaginationBundle::MODE_AUTO:
                $this->doAddNewsPagination($template, $article, $module);
                break;
            case NewsPaginationBundle::MODE_MANUAL:
                $this->doAddManualNewsPagination($template, $article, $module);
                break;
            case NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK:
                $this->doAddManualNewsPagination($template, $article, $module);

                if (!static::$manualPaginationFound) {
                    $this->doAddNewsPagination($template, $article, $module);
                }
                break;
        }
    }

    public function doAddManualNewsPagination(Template $template, array $article, Module $module)
    {
        $pageParam = 'page_n' . $module->id;
        $page      = Request::getGet($pageParam) ?: 1;
        $maxIndex  = 0;

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $node          = new HtmlPageCrawler('<div><div class="news-pagination-content">' . StringUtil::restoreBasicEntities($template->text) . '</div></div>');
        $startElements = $node->filter('.news-pagination-content > [class*="ce_news_pagination_start"]');

        if ($startElements->count() < 1) {
            return;
        }

        static::$manualPaginationFound = true;

        $startElements->each(
            function ($element) use ($page, &$maxIndex) {
                $intIndex = $element->getAttribute('data-index');

                if ($intIndex > $maxIndex) {
                    $maxIndex = $intIndex;
                }

                if ($page != $intIndex) {
                    $element->remove();
                }
            }
        );

        $template->text = str_replace(['%7B', '%7D'], ['{', '}'], $node->saveHTML());

        // add pagination
        $pagination               = new Pagination($maxIndex, 1, Config::get('maxPaginationLinks'), $pageParam);
        $template->newsPagination = $pagination->generate("\n  ");

        // path without query string
        $path = \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getPathInfo();
        $url  = Environment::get('url') . $path;

        $this->handleMetaTags($maxIndex, $page, $pageParam, $module, $article['alias'], $url);
    }

    public function doAddNewsPagination(Template $template, array $article, Module $module)
    {
        $pageParam   = 'page_n' . $module->id;
        $currentPage = is_numeric(Request::getGet($pageParam)) && Request::getGet($pageParam) > 0 ? Request::getGet($pageParam) : 1;

        $maxAmount = $module->paginationMaxCharCount;
        $pageCount = 1;

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $node              = new HtmlPageCrawler('<div><div class="news-pagination-content">' . StringUtil::restoreBasicEntities($template->text) . '</div></div>');
        $textAmount        = 0;
        $ceTextCssSelector = $module->paginationCeTextCssSelector;
        $tags              = static::$tags;

        // replace multiple br elements to
        $node->filter('.news-pagination-content > [class*="ce_"]')->each(
            function ($element) use (&$textAmount, $maxAmount, $tags, $node, $ceTextCssSelector) {
                if (strpos($element->getAttribute('class'), 'ce_text') !== false && strpos($element->html(), 'figure') === false) {
                    $element->children($ceTextCssSelector . ', figure')->each(
                        function ($paragraph) use (&$textAmount, $maxAmount, $tags) {
                            $paragraph->html(preg_replace('@<br\s?\/?><br\s?\/?>@i', '</p><p>', $paragraph->html()));
                        });
                }
            }
        );

        // pagination
        $node     = new HtmlPageCrawler($node->saveHTML());
        $elements = [];

        // get relevant elements
        $node->filter('.news-pagination-content > [class*="ce_"]')->each(
            function ($element) use (&$textAmount, $maxAmount, $tags, $node, $ceTextCssSelector, &$pageCount, &$elements) {
                if (strpos($element->getAttribute('class'), 'ce_text') !== false &&
                    strpos($element->html(), 'figure') === false
                ) {
                    if ($ceTextCssSelector) {
                        $element = $element->filter($ceTextCssSelector);
                    }

                    $element->children()->each(
                        function ($objElement) use (&$intTextAmount, $textAmount, $tags, &$pageCount, &$elements) {
                            $elements[] = [
                                'element' => $objElement,
                                'text'    => $objElement->text(),
                                'tag'     => $objElement->nodeName(),
                                'length'  => strlen($objElement->text())
                            ];
                        }
                    );
                } else {
                    $elements[] = [
                        'element' => $element,
                        'text'    => $element->text(),
                        'tag'     => $element->nodeName(),
                        'length'  => 0
                    ];
                }
            }
        );

        // split array by text amounts
        $splitted = [];

        foreach ($elements as $element) {
            $textAmountOrigin = $textAmount;
            $textAmount       += $element['length'];

            if ($textAmount > $maxAmount && $textAmountOrigin != 0) {
                $pageCount++;
                $textAmount = $element['length'];
            }

            if (!isset($splitted[$pageCount])) {
                $splitted[$pageCount] = [];
            }

            $splitted[$pageCount][] = $element;
        }

        // hold together headlines and paragraphs
        if ($module->avoidTrailingHeadlines) {
            $result = [];

            foreach ($splitted as $page => $parts) {
                $headlines         = [];
                $nonHeadlines      = [];
                $trailingHeadlines = true;

                for ($i = count($parts) - 1; $i > -1; $i--) {
                    if ($trailingHeadlines && in_array($parts[$i]['tag'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'h7', 'h8', 'h9', 'h10'])) {
                        $headlines[] = $parts[$i];
                    } else {
                        // break overlap handling if headline is not trailing
                        $trailingHeadlines = false;
                        $nonHeadlines[]    = $parts[$i];
                    }
                }

                if (empty($result[$page])) {
                    $result[$page] = array_reverse($nonHeadlines);
                } else {
                    $result[$page] = array_merge($result[$page], array_reverse($nonHeadlines));
                }

                if (!empty($headlines)) {
                    if (empty($result[$page + 1])) {
                        $result[$page + 1] = array_reverse($headlines);
                    } else {
                        $result[$page + 1] = array_merge(array_reverse($headlines), $result[$page + 1]);
                    }
                }
            }
        } else {
            $result = $splitted;
        }

        // can't be ;-)
        if ($currentPage > $pageCount) {
            $currentPage = $pageCount;
        }

        foreach ($result as $page => $parts) {
            foreach ($parts as $part) {
                if ($currentPage) {
                    if ($page != $currentPage) {
                        $part['element']->remove();
                    }
                } else {
                    if ($page != 1) {
                        $part['element']->remove();
                    }
                }
            }
        }

        $template->text = str_replace(['%7B', '%7D'], ['{', '}'], $node->saveHTML());

        // add pagination
        $pagination               = new Pagination($pageCount, 1, Config::get('maxPaginationLinks'), $pageParam);
        $template->newsPagination = $pagination->generate("\n  ");

        // add head info

        /** @var $objPage \Contao\PageModel */
        global $objPage;

        // path without query string
        $path = \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getPathInfo();
        $url  = Environment::get('url') . $path;

        // if path is id, take absolute url from current page
        if (is_numeric(ltrim($path, '/'))) {
            $url = $objPage->getAbsoluteUrl();
        }

        $this->handleMetaTags($pageCount, $currentPage, $pageParam, $module, $article['alias'], $url);
    }

    public function handleMetaTags(int $pageCount, int $currentPage, string $pageParam, Module $module, string $alias, string $url)
    {
        if ($pageCount > 1) {
            $canonical = $this->linkCanonical->getContent();

            // canonical link must contain the current news url or not set
            if ($module->addFullVersionCanonicalLink && $module->fullVersionGetParameter && (!$canonical || strpos($canonical, urlencode($alias)) !== false)) {
                $this->linkCanonical->setContent(
                    $this->urlUtil->addQueryString($module->fullVersionGetParameter . '=1', $url)
                );
            }

            // prev and next links
            if ($module->addPrevNextLinks) {
                if ($currentPage == 1) {
                    $this->linkPrev->setContent('');

                    $this->linkNext->setContent(
                        $this->urlUtil->addQueryString($pageParam . '=2', $url)
                    );
                } elseif ($currentPage > 1 && $currentPage < $pageCount) {
                    $this->linkPrev->setContent(
                        $this->urlUtil->addQueryString($pageParam . '=' . ($currentPage - 1), $url)
                    );

                    $this->linkNext->setContent(
                        $this->urlUtil->addQueryString($pageParam . '=' . ($currentPage + 1), $url)
                    );
                } else {
                    if ($currentPage >= $pageCount) {
                        $this->linkPrev->setContent(
                            $this->urlUtil->addQueryString($pageParam . '=' . ($pageCount - 1), $url)
                        );

                        $this->linkNext->setContent('');
                    }
                }
            }
        }
    }
}

