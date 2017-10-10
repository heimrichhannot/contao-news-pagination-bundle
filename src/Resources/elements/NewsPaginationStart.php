<?php

namespace HeimrichHannot\NewsPagination;


class NewsPaginationStart extends \ContentElement
{
    protected $strTemplate = 'ce_news_pagination_start';

    protected static $arrElementsCache = [];

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
        $arrElements = static::$arrElementsCache;

        if (empty($arrElements))
        {
            // get index in current news
            $objElements =
                \Database::getInstance()->prepare('SELECT id FROM tl_content WHERE type=? AND ptable=? AND pid=? ORDER BY sorting')->execute(
                    $this->type,
                    $this->ptable,
                    $this->pid
                );

            if ($objElements->numRows > 0)
            {
                $arrElements = $objElements->fetchEach('id');
            }
        }

        $intIndex              = array_search($this->id, $arrElements);
        $this->Template->index = $intIndex > -1 ? $intIndex + 1 : 0;
    }
}