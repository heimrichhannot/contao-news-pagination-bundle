<?php

namespace HeimrichHannot\NewsPagination\Backend;


use HeimrichHannot\NewsPagination\NewsPagination;
use HeimrichHannot\Request\Request;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class Content extends \Backend
{
    public static function addNewsPaginationStopElement(\DataContainer $objDc)
    {
        if (($objElement = \ContentModel::findByPk($objDc->id)) === null
            || $objElement->type !== NewsPagination::CONTENT_ELEMENT_NEWS_PAGINATION_START
            || $objElement->newsPaginationStopCreated
        )
        {
            return;
        }

        $objElement->newsPaginationStopCreated = true;
        $objElement->save();

        $objElements = \ContentModel::findBy(
            ['tl_content.ptable=?', 'tl_content.pid=?'],
            [
                $objElement->ptable,
                $objElement->pid
            ],
            ['order' => 'tl_content.sorting']
        );

        // create the stop element
        $objStop = new \ContentModel();

        $objStop->tstamp  = time();
        $objStop->ptable  = $objElement->ptable;
        $objStop->pid     = $objElement->pid;
        $objStop->sorting = $objElement->sorting;
        $objStop->type    = NewsPagination::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;

        $objStop->save();

        // update sorting
        $i = 0;

        if ($objElements !== null)
        {
            $arrIds = $objElements->fetchEach('id');

            array_insert($arrIds, array_search($objDc->id, $arrIds) + 1, [
                $objStop->id
            ]);

            foreach ($arrIds as $intId)
            {
                \Database::getInstance()->prepare('UPDATE tl_content SET sorting=? WHERE id=?')->execute(++$i * 128, $intId);
            }
        }
    }
}