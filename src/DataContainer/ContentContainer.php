<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\DataContainer;

use Contao\ContentModel;
use Contao\Database;
use Contao\DataContainer;
use HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class ContentContainer
{
    /**
     * @var ModelUtil
     */
    private $modelUtil;

    public function __construct(ModelUtil $modelUtil)
    {
        $this->modelUtil = $modelUtil;
    }

    public function addNewsPaginationStopElement(DataContainer $dc)
    {
        if (null === ($element = $this->modelUtil->findModelInstanceByPk('tl_content', $dc->id))
            || NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START !== $element->type
            || $element->newsPaginationStopCreated
        ) {
            return;
        }

        $element->newsPaginationStopCreated = true;
        $element->save();

        $elements = $this->modelUtil->findModelInstancesBy('tl_content',
            ['tl_content.ptable=?', 'tl_content.pid=?'],
            [
                $element->ptable,
                $element->pid,
            ],
            ['order' => 'tl_content.sorting']
        );

        // create the stop element
        $stop = new ContentModel();

        $stop->tstamp = time();
        $stop->ptable = $element->ptable;
        $stop->pid = $element->pid;
        $stop->sorting = $element->sorting;
        $stop->type = NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP;

        $stop->save();

        // update sorting
        $i = 0;

        if (null !== $elements) {
            $ids = $elements->fetchEach('id');

            array_insert($ids, array_search($dc->id, $ids) + 1, [
                $stop->id,
            ]);

            foreach ($ids as $id) {
                Database::getInstance()->prepare('UPDATE tl_content SET sorting=? WHERE id=?')->execute(++$i * 128, $id);
            }
        }
    }
}
