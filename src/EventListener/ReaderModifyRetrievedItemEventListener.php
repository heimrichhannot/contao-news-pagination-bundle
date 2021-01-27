<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\EventListener;

use HeimrichHannot\ReaderBundle\Event\ReaderModifyRetrievedItemEvent;

class ReaderModifyRetrievedItemEventListener
{
    public function __invoke(ReaderModifyRetrievedItemEvent $event)
    {
        $readerConfig = $event->getReaderConfig();

        $GLOBALS['HUH_NEWS_PAGINATION']['CONFIG'] = $readerConfig->paginationMode ? $readerConfig : null;
    }
}
