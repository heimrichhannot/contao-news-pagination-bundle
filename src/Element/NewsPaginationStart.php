<?php

namespace HeimrichHannot\NewsPaginationBundle\Element;


use Contao\ContentElement;

class NewsPaginationStart extends ContentElement
{
    protected $strTemplate = 'ce_news_pagination_start';

    protected static $arrElementsCache = [];

    public function generate()
    {
        if (TL_MODE == 'BE') {
            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {
        $elements = static::$arrElementsCache;

        if (empty($elements)) {
            // get index in current news
            $elementObj =
                \Database::getInstance()->prepare('SELECT id FROM tl_content WHERE type=? AND ptable=? AND pid=? ORDER BY sorting')->execute(
                    $this->type,
                    $this->ptable,
                    $this->pid
                );

            if ($elementObj->numRows > 0) {
                $elements = $elementObj->fetchEach('id');
            }
        }

        $index                 = array_search($this->id, $elements);
        $this->Template->index = $index > -1 ? $index + 1 : 0;
    }
}