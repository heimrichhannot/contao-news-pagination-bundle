<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\Util;

use Wa72\HtmlPageDom\HtmlPageCrawler;

class PaginationUtil
{
    /**
     * Paginate a html text.
     *
     * Options:
     * - selector: (string) Override the container selector. Default: .ce_text
     * - avoidTrailingHeadlines: (bool) Set if trailing headlines should be avoided. Default true
     * - removePageElementsCallback: (callable) A callback to evaluate if the elements of an page should be removed from result text. Default: `return $page != $currentPage`;
     *
     * @return PaginationResult an object containing all necessary result values
     */
    public function paginateHtmlText(string $html, int $limit, int $currentPage, array $options = []): PaginationResult
    {
        $defaults = [
            'selector' => '.ce_text',
            'avoidTrailingHeadlines' => true,
            'removePageElementsCallback' => function (array $result, int $currentPage, int $page, array $elements) { return $page != $currentPage; },
        ];
        $options = array_merge($defaults, $options);

        $pageCount = 1;
        $textAmount = 0;
        $ceTextCssSelector = $options['selector'];

        // add wrapper div since remove() called on root elements doesn't work (bug?)
        $node = new HtmlPageCrawler('<div><div class="news-pagination-content">'.\Contao\StringUtil::restoreBasicEntities($html).'</div></div>');

        // replace multiple br elements to
        $node->filter('.news-pagination-content > [class*="ce_"]')->each(function ($element) use (&$textAmount, $ceTextCssSelector) {
            /** @var HtmlPageCrawler $element  */
            if (false !== strpos($element->getAttribute('class'), 'ce_text') && false === strpos($element->html(), 'figure')) {
                $element->children($ceTextCssSelector.', figure')->each(function ($paragraph) use (&$textAmount) {
                    /** @var HtmlPageCrawler $paragraph  */
                    $paragraph->setInnerHtml(preg_replace('@<br\s?\/?><br\s?\/?>@i', '</p><p>', $paragraph->html()));
                });
            }
        });

        // pagination
        $node = new HtmlPageCrawler($node->saveHTML());
        $elements = [];

        // get relevant elements

        $node->filter('.news-pagination-content > [class*="ce_"]')->each(function ($element) use (&$textAmount, $ceTextCssSelector, &$pageCount, &$elements) {
            if (false !== strpos($element->getAttribute('class'), 'ce_text')
                && false === strpos($element->html(), 'figure')) {
                if ($ceTextCssSelector) {
                    $element = $element->filter($ceTextCssSelector);
                }

                $element->children()->each(function ($element) use (&$intTextAmount, &$pageCount, &$elements) {
                    /** @var HtmlPageCrawler $element  */
                    if (method_exists($element, 'getCombinedText')) {
                        $text = $element->getCombinedText();
                    } else {
                        $text = $element->text();
                    }

                    /** @var HtmlPageCrawler $element  */
                    $elements[] = [
                        'element' => $element,
                        'text' => trim($text),
                        'tag' => $element->nodeName(),
                        'length' => \strlen($text),
                    ];
                });
            } else {
                if (method_exists($element, 'getCombinedText')) {
                    $text = $element->getCombinedText();
                } else {
                    $text = $element->text();
                }
                $elements[] = [
                    'element' => $element,
                    'text' => trim($text),
                    'tag' => $element->nodeName(),
                    'length' => 0,
                ];
            }
        });
        // split array by text amounts
        $splitted = [];

        foreach ($elements as $element) {
            $textAmountOrigin = $textAmount;
            $textAmount += $element['length'];

            if ($textAmount > $limit && 0 != $textAmountOrigin) {
                ++$pageCount;
                $textAmount = $element['length'];
            }

            if (!isset($splitted[$pageCount])) {
                $splitted[$pageCount] = [];
            }

            $splitted[$pageCount][] = $element;
        }

        // hold together headlines and paragraphs
        if ($options['avoidTrailingHeadlines']) {
            $result = [];

            foreach ($splitted as $page => $parts) {
                $headlines = [];
                $nonHeadlines = [];
                $trailingHeadlines = true;

                for ($i = \count($parts) - 1; $i > -1; --$i) {
                    if ($trailingHeadlines && \in_array($parts[$i]['tag'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'h7', 'h8', 'h9', 'h10'])) {
                        $headlines[] = $parts[$i];
                    } else {
                        // break overlap handling if headline is not trailing
                        $trailingHeadlines = false;
                        $nonHeadlines[] = $parts[$i];
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
            if (true === \call_user_func($options['removePageElementsCallback'], $result, $currentPage, $page)) {
                foreach ($parts as $part) {
                    $part['element']->remove();
                }
            }
        }

        $paginationResult = new PaginationResult();
        $paginationResult->setText(str_replace(['%7B', '%7D'], ['{', '}'], $node->saveHTML()));
        $paginationResult->setCurrentPage($currentPage);
        $paginationResult->setPageCount($pageCount);
        $paginationResult->setPages($result);

        return $paginationResult;
    }
}
