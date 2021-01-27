<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\EventListener;

use Contao\Template;
use HeimrichHannot\NewsPaginationBundle\Manager\ContentNavigationManager;
use HeimrichHannot\NewsPaginationBundle\Manager\NewsPaginationManager;
use HeimrichHannot\UtilsBundle\String\StringUtil;

class ParseTemplateListener
{
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var ContentNavigationManager
     */
    protected $contentNavigationManager;
    /**
     * @var NewsPaginationManager
     */
    private $manager;

    public function __construct(NewsPaginationManager $manager, StringUtil $stringUtil, ContentNavigationManager $contentNavigationManager)
    {
        $this->manager = $manager;
        $this->stringUtil = $stringUtil;
        $this->contentNavigationManager = $contentNavigationManager;
    }

    public function __invoke(Template $template)
    {
        if (!class_exists('Hofff\Contao\ContentNavigation\HofffContentNavigationBundle') ||
            !$this->stringUtil->startsWith($template->getName(), 'ce_hofff_content_navigation')) {
            return;
        }

        // adjust links for activate manual pagination
        $this->contentNavigationManager->adjustLinks($template);
    }
}
