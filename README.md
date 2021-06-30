# News Pagination

This bundle offers automatic content pagination for the core news reader module and the [heimrichhannot/contao-reader-bundle](https://github.com/heimrichhannot/contao-reader-bundle).

## Features

- add a pagination for navigating between the news parts
- automatic splitting
    - split news articles by an adjustable character amount respecting html tags
- manual splitting
    - split news content by wrapping the content elements in special start and stop content elements
- optional support for [hofff/contao-content-navigation](https://github.com/hofff/contao-content-navigation)

## Usage

### Setup

1. Install with composer or contao manager

        composer require heimrichhannot/contao-news-pagination-bundle

1. Update database
1. You'll find new configuration options in the news reader frontend module or your reader configuration
1. Add `<?= $this->newsPagination ?>` to your details template (e.g. "news_full") to output the pagination navigation

### Known limitations for automatic pagination

- currently only ce_text not nested in another content element (like an accordion) is supported for splitting, other elements are removed properly according to current page number though (completely)

## Developers

### PaginationUtil

The script to do the automatic pagination can be used by developers by using the `PaginationUtil`.

Example:

```php
use HeimrichHannot\NewsPaginationBundle\Util\PaginationUtil;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class ParseArticlesListener {
    /** @var PaginationUtil */
    protected $paginationUtil;
    
    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module) {
        $result = $this->paginationUtil->paginateHtmlText($template->text, 500, 1, [
                'selector' => '.ce_text_custom div.text',
                'removePageElementsCallback' => function (array $result, int $currentPage, int $page) {
                    // Always show page 1
                    if (1 === $page) {
                        return false;
                    }
                    // Insert custom html after page 3
                    if (3 === $page) {
                        $someCustomInsertion = new HtmlPageCrawler('<div class="alert">Custom Notice!</div>');
                        $someCustomInsertion->insertAfter(end($result[$page])['element']);
                    }
                    // Remove all element not on the current page (default)
                    return $page != $currentPage;
                }
            ]);
    }
}
```

