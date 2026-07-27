# Changelog

All notable changes to `filament-typo3` will be documented in this file.

## [Unreleased]

### Added
- Added comprehensive PHPDoc documentation for all public classes and methods
- Added type safety improvements with generic type hints
- Added new configuration options for access control and caching
- Added GitHub Actions workflow for CI/CD (tests, PHPStan, Pint)
- Added new composer scripts for analysis, linting, and fixing

### Changed
- Reorganized file structure: moved Enums to `src/Enums/`, Actions to `src/Actions/`
- Improved caching strategy for schema checks (TTL-based instead of forever)
- Enhanced code documentation and comments
- Updated composer.json with PHP 8.1+ requirement and optimization settings
- Improved error handling and method signatures

### Deprecated
- Nothing deprecated in this release

### Removed
- Removed unnecessary magic properties and methods
- Removed redundant comments and code

### Fixed
- Fixed potential N+1 query issues in Node component
- Fixed cache key collisions in schema checks

### Security
- No security-related changes in this release

## [1.0.3] - 2024-XX-XX

### Added
- Initial release of filament-typo3 package
- TYPO3 Access Tab functionality with migration helpers, form component, and query scope
- TYPO3 SEO Tab functionality
- Page tree view with Livewire components
- Factory states trait for testing
- Bulk actions for show/hide functionality

[Unreleased]: https://github.com/egg2-code-labs/filament-typo3/compare/1.0.3...HEAD
[1.0.3]: https://github.com/egg2-code-labs/filament-typo3/releases/tag/1.0.3
