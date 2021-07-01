<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\NewsPaginationBundle\Util;

class PaginationResult
{
    /** @var string */
    protected $text;
    /** @var int */
    protected $currentPage;
    /** @var int */
    protected $pageCount;
    /** @var array */
    protected $pages;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function setPages(array $pages): void
    {
        $this->pages = $pages;
    }
}
