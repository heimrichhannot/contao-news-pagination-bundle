<?php
/**
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPagination\Backend;


use Contao\Controller;
use Contao\System;

class Backend
{

    public function getPaginationTemplate()
    {
        return System::getContainer()->get('contao.framework')->getAdapter(Controller::class)->getTemplateGroup('pagination');
    }
}