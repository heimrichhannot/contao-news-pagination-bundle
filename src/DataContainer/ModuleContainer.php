<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\DataContainer;

use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;

class ModuleContainer
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public function getPaginationTemplate()
    {
        return $this->framework->getAdapter(Controller::class)->getTemplateGroup('pagination');
    }
}
