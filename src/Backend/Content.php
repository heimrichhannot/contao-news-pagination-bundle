<?php

namespace HeimrichHannot\NewsPagination\Backend;


use Contao\ContentModel;
use Contao\Database;
use Contao\DataContainer;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;

class Content extends \Backend
{
    public static function addNewsPaginationStopElement(DataContainer $dc)
    {
        if (($element = ContentModel::findByPk($dc->id)) === null
            || $element->type !== NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START
            || $element->newsPaginationStopCreated
        )
        {
            return;
        }

        $element->newsPaginationStopCreated = true;
        $element->save();

        $elements = ContentModel::findBy(
            ['tl_content.ptable=?', 'tl_content.pid=?'],
            [
                $element->ptable,
                $element->pid
            ],
            ['order' => 'tl_content.sorting']
        );

        // create the stop element
        $stop = new ContentModel();

        $stop->tstamp  = time();
        $stop->ptable  = $element->ptable;
        $stop->pid     = $element->pid;
        $stop->sorting = $element->sorting;
        $stop->type    = NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;

        $stop->save();

        // update sorting
        $i = 0;

        if ($elements !== null)
        {
            $ids = $elements->fetchEach('id');

            array_insert($ids, array_search($dc->id, $ids) + 1, [
                $stop->id
            ]);

            foreach ($ids as $id)
            {
                Database::getInstance()->prepare('UPDATE tl_content SET sorting=? WHERE id=?')->execute(++$i * 128, $id);
            }
        }
    }
}