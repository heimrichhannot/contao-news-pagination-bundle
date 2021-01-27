<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\Element;

use Contao\ContentElement;

class NewsPaginationStop extends ContentElement
{
    protected $strTemplate = 'ce_news_pagination_stop';

    public function generate()
    {
        if (TL_MODE == 'BE') {
            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {
    }
}
