# Changelog
All notable changes to this project will be documented in this file.

## [2.2.0] - 2020-05-15
- class refactoring
- added optional support for reader-bundle

## [2.1.12] - 2020-04-01
- fixed constructor

## [2.1.11] - 2020-04-01
- fixed service definition

## [2.1.10] - 2020-04-01
- removed usages of heimrichhannot/contao-request in favor of heimrichhannot/contao-request-bundle

## [2.1.9] - 2018-07-26

### Fixed
- symfony 4 support, make service public, thanks to @Paddy0174 (https://github.com/heimrichhannot/contao-news-pagination-bundle/pull/1)

## [2.1.8] - 2018-07-26

### Fixed
- do not set `prev` or `next` header links if empty (rollback from 2.1.3)
- add Manager Plugin order to load after `heimrichhannot/contao-header-bundle` 

## [2.1.7] - 2018-07-26

### Fixed
- getPaginationTemplate

## [2.1.6] - 2018-07-26

### Fixed
- set linkCanoical if no canonical link isset

## [2.1.5] - 2018-07-06

### Fixed
- fixed options_callback

## [2.1.4] - 2018-06-28

### Changed
- removed singlepage_pagination and added custom pagination template

## [2.1.3] - 2018-06-04

### Fixed
- canonical tag and prev/next

## [2.1.2] - 2018-04-20

### Added
- templateTextPagination

## [2.1.1] - 2018-04-17

### Added
- singlepage_pagination template

### Changed
- addPagination function

## [2.1.0] - 2018-03-06

### Added
- textual pagination
- refactoring
- dependency to heimrichhannot/contao-utils-bundle

### Removed
- unnecessary redundant dependencies

## [2.0.1] - 2018-03-06

### Fixed
- urls with urldecodable characters
- prev and next links for first and last pages

## [2.0.0] - 2018-03-06

### Added
- disjoint paginationMode
- new mode "manual with automatic fallback"
- dependency to heimrichhannot/contao-request
- meta tag handling for manual pagination

### Changed
- dependency from heimrichhannot/contao-haste_plus to heimrichhannot/contao-utils-bundle

## [1.0.6] - 2018-01-17

### Added
- semaphore for manual vs. automatic pagination

## [1.0.5] - 2017-12-06

### Fixed
- syntax error

## [1.0.4] - 2017-12-06

### Added
- manual pagination

## [1.0.3] - 2017-12-04

### Fixed
- entity escaping

## [1.0.2] - 2017-10-20

### Fixed
- do not add `print` to canonical url if news was relocated

## [1.0.1] - 2017-10-16

### Fixed
- namespace issues

## [1.0.0] - 2017-10-13

### Added
- initial commit
