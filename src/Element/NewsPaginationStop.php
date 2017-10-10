<?php

namespace HeimrichHannot\NewsPagination;


class NewsPaginationStop extends \ContentElement
{

    protected $strTemplate = 'ce_news_pagination_stop';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {
    }
}