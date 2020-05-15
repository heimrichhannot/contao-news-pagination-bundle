<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\EventListener;

use Contao\Module;
use Contao\Template;
use HeimrichHannot\NewsPaginationBundle\Manager\NewsPaginationManager;

class HookListener
{
    /**
     * @var NewsPaginationManager
     */
    private $manager;

    public function __construct(NewsPaginationManager $manager)
    {
        $this->manager = $manager;
    }

    public function addNewsPagination(Template $template, array $article, Module $module)
    {
        $this->manager->addNewsPagination($template, $article, $module);
    }
}
