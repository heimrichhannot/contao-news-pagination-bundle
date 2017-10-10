# News Pagination

This bundle offers content pagination for the news reader.

## Features

- add a pagination for navigating between the news parts
- manual splitting
    - split news content by wrapping the content elements in special start and stop content elements
- automatic splitting
    - split news articles by an adjustable character amount considering html tags

### Technical instructions

1. Activate "addPagination" in your news reader module.
2. Add `<?= $this->newsPagination ?>` to your details template (e.g. "news_full").

### Known limitations for automatic pagination

- currently only ce_text not nested in another content element (like an accordeon) is supported for splitting, other elements are removed properly according to current page number though (completely)
