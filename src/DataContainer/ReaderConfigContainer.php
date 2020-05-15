<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\DataContainer;

use Contao\DataContainer;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class ReaderConfigContainer
{
    /**
     * @var ModelUtil
     */
    private $modelUtil;

    public function __construct(ModelUtil $modelUtil)
    {
        $this->modelUtil = $modelUtil;
    }

    public function modifyDca(DataContainer $dc)
    {
        $readerConfig = $this->modelUtil->findModelInstanceByPk('tl_reader_config', $dc->id);
        $dca = &$GLOBALS['TL_DCA']['tl_reader_config'];

        if ($readerConfig && 'tl_news' === $readerConfig->dataContainer) {
            $dca['palettes']['default'] = str_replace('{template_legend', '{pagination_legend},paginationMode;{template_legend', $dca['palettes']['default']);
        }
    }
}
