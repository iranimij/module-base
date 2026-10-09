# Changelog

All notable changes to this project are documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [1.0.0] - 2026-10-09

### Added
- "Iranimij" tab in Stores › Configuration (`etc/adminhtml/system.xml`) with ACL resource `Iranimij_Base::config`; products add their own sections under it.
- System › Iranimij › Installed Modules page listing every `Iranimij_*` module with package, version, status, documentation and changelog links, read from local files only (ACL `Iranimij_Base::modules`).
- `Iranimij\Base\Model\Config\TypedReader`: store-scoped `getString`, `getInt`, `getFloat`, `getBool`, `getList`.
- `Iranimij\Base\Serializer\SafeJson`: `encode` and `decode` that throw `\InvalidArgumentException` instead of returning false or null; `decode` always returns an array.
- Unit test guarding the promise that the module stays under 1,000 lines of PHP.

### Promise
No license checks. No phone-home. No telemetry. No admin notifications. No ads. No business logic. Strict semver.

[Unreleased]: https://github.com/iranimij/module-base/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/iranimij/module-base/releases/tag/v1.0.0
