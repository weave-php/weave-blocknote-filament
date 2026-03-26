# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- PHPUnit suite, GitHub Actions workflow, and development dependencies (`orchestra/testbench`, `phpunit`).
- `Weave\BlockNote\Filament\BlockNotePlugin` for `Panel::plugin()`.
- `Weave\BlockNote\Tables\Columns\BlockNoteColumn` and `Weave\BlockNote\Infolists\Components\BlockNoteEntry` for plain-text previews.
- `Weave\BlockNote\Support\BlockNoteDocument::toPlainText()` and `Weave\BlockNote\Rules\BlockNoteDocumentRule`.
- Upload route: configurable **`throttle`** middleware and optional **`authorize`** callback (via `config()` at runtime).
- Explicit BlockNote locale map in the JS bundle so dictionaries such as French resolve reliably.
- Translatable validation messages for `BlockNoteDocumentRule` (`weave-blocknote::validation.blocknote_document.*`, English and French).

### Changed

- **Composer:** require `filament/filament` instead of `filament/forms` only (aligns versions across panel, tables, and infolists).
- **Filament assets** registered under the identifier `weave-php/blocknote-filament` (published paths under `public/js/weave-php/blocknote-filament/` and `public/css/weave-php/blocknote-filament/`). Re-run `php artisan filament:assets` after upgrading if you used the previous path.
- **`blockNoteLocale()`** as the preferred API; **`locale()`** kept as a deprecated alias for the BlockNote UI language.
