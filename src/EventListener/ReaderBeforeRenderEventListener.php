<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\EventListener;

use HeimrichHannot\NewsPaginationBundle\Manager\NewsPaginationManager;
use HeimrichHannot\ReaderBundle\Event\ReaderBeforeRenderEvent;

class ReaderBeforeRenderEventListener
{
    /**
     * @var NewsPaginationManager
     */
    private $manager;

    public function __construct(NewsPaginationManager $manager)
    {
        $this->manager = $manager;
    }

    public function addNewsPagination(ReaderBeforeRenderEvent $event)
    {
        $context = (object) $event->getTemplateData();
        $this->manager->addNewsPagination($context, $event->getItem()->getFormatted(), $event->getReaderConfig());

        $event->setTemplateData((array) $context);
    }
}
