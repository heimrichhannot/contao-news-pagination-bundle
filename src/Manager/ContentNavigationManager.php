<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\Manager;

use Contao\FrontendTemplate;
use Contao\System;
use Contao\Template;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use HeimrichHannot\RequestBundle\Component\HttpFoundation\Request;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;
use Hofff\Contao\ContentNavigation\Navigation\ContentNavigationBuilder;

class ContentNavigationManager
{
    /**
     * @var ModelUtil
     */
    protected $modelUtil;
    /**
     * @var UrlUtil
     */
    protected $urlUtil;
    /**
     * @var Request
     */
    protected $request;

    public function __construct(ModelUtil $modelUtil, UrlUtil $urlUtil, Request $request)
    {
        $this->modelUtil = $modelUtil;
        $this->urlUtil = $urlUtil;
        $this->request = $request;
    }

    public function adjustLinks(Template $template)
    {
        // set in ReaderModifyRetrievedItemEventListener
        if (!($pageParam = $GLOBALS['HUH_NEWS_PAGINATION']['CONFIG'])) {
            return;
        }

        $pageParam = 'page_n'.$pageParam->id;

        $data = $template->getData();

        $items = System::getContainer()->get(ContentNavigationBuilder::class)->fromParent(
            (string) $data['ptable'],
            (int) $data['pid'],
            (int) $data['hofff_toc_min_level'],
            (int) $data['hofff_toc_max_level'],
            (bool) $data['hofff_toc_force_request_uri']
        );

        $columns = [
            'tl_content.ptable=?',
            'tl_content.pid=?',
        ];

        $this->modelUtil->addPublishedCheckToModelArrays(
            'tl_content', 'invisible', 'start', 'stop', $columns, [
                'invertPublishedField' => true,
        ]);

        if (null === ($elements = $this->modelUtil->findModelInstancesBy('tl_content', $columns, [
                $data['ptable'],
                $data['pid'],
            ], ['order' => 'sorting ASC']))) {
            return;
        }

        $pageCount = 0;
        $tocCount = 0;
        $mapping = [];

        while ($elements->next()) {
            if (NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START === $elements->type) {
                ++$pageCount;
            }

            if ($elements->hofff_toc_include) {
                $mapping[$elements->id] = $pageCount;
                ++$tocCount;
            }
        }

        // apply mapping
        $items = $this->adjustLinkInItems($items, $pageParam, $mapping);

        $data['items'] = $this->parseItems($items);

        $template->setData($data);
    }

    protected function adjustLinkInItems(array $items, string $pageParam, array $mapping)
    {
        foreach ($items as &$item) {
            $item['href'] = $this->addPaginationParamToUrl($item['href'], $pageParam, $mapping[$item['id']]);

            if (isset($item['subitems'])) {
                $item['subitems'] = $this->adjustLinkInItems($item['subitems'], $pageParam, $mapping);
            }
        }

        return $items;
    }

    protected function addPaginationParamToUrl(string $url, string $pageParam, int $pageNum): string
    {
        $parts = explode('#', $url);

        if (\count($parts) < 2) {
            return $url;
        }

        if ($pageNum < 2) {
            return $this->urlUtil->removeQueryString([$pageParam], $parts[0]).'#'.$parts[1];
        }

        $url = $this->urlUtil->addQueryString("$pageParam=$pageNum", $parts[0]).'#'.$parts[1];

        return $url;
    }

    /**
     * Copied from hofff's bundle.
     */
    private function parseItems(array $items, int $level = 1): string
    {
        if (!\count($items)) {
            return '';
        }

        foreach ($items as &$item) {
            if (isset($item['subitems'])) {
                $item['subitems'] = $this->parseItems($item['subitems'], $level + 1);
            }
            $item['class'] = '';
        }

        $items[0]['class'] = 'first';
        $items[\count($items) - 1]['class'] = 'last';

        $tpl = new FrontendTemplate('hofff_content_nav_default');
        $tpl->setData(['items' => $items, 'level' => $level]);

        return $tpl->parse();
    }
}
