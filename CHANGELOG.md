# Changelog
All notable changes to this project will be documented in this file.

## [2.5.7] - 2023-09-18
- Fixed: added missing dependencies ([#8])

## [2.5.6] - 2023-03-02
- Fixed: missing parameter on user function call ([#7], [@cliffparnitzky])

## [2.5.5] - 2023-02-09
- Changed: reduces database size usage in tl_module

## [2.5.4] - 2022-10-13
- Fixed: compatibility problem with wa72/htmlpagedom v2

## [2.5.3] - 2022-09-02
- Changed: allow php 8
- Fixed: inserttags with flags lead to empty strings

## [2.5.2] - 2022-02-16
- Fixed: user group issue

## [2.5.1] - 2022-01-13
- Fixed: w72/htmlpagedom v1 compatibility

## [2.5.0] - 2022-01-13
- Changed: allow wa72/htmlpagedom v2

## [2.4.0] - 2021-07-01
- Add pagination util ([#6])
- increased minimum php version to 7.1
- added license file and updated license
- some cleanup

## [2.3.0] - 2021-01-27
- added support for hofff/contao-content-navigation (if installed)
- service refactoring

## [2.2.1] - 2020-06-18
- respect already set canonical link (wrong bool comparison)

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
- symfony 4 support, make service public, thanks to @Paddy0174 ([#1])

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

[@cliffparnitzky]: https://github.com/cliffparnitzky
[#1]: https://github.com/heimrichhannot/contao-news-pagination-bundle/pull/1
[#6]: https://github.com/heimrichhannot/contao-news-pagination-bundle/pull/6
[#7]: https://github.com/heimrichhannot/contao-news-pagination-bundle/pull/7
[#8]: https://github.com/heimrichhannot/contao-news-pagination-bundle/issues/8
