# Laravel Adapter for `vs-version-incrementor`

[RU](https://github.com/Voral/laravel-version-increment/blob/master/README.ru.md)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Voral/laravel-version-increment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Voral/laravel-version-increment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Voral/laravel-version-increment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Voral/laravel-version-increment/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Voral/laravel-version-increment/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence) \
![PHP Tests](https://github.com/Voral/laravel-version-increment/actions/workflows/php.yml/badge.svg)

This package provides a **native Artisan command** for [`vs-version-incrementor`](https://github.com/Voral/vs-version-incrementor) — a tool for automated version management and `CHANGELOG.md`
generation based on Git commit analysis.

Now you can leverage all features of `vs-version-incrementor` directly from the Laravel console — without manually
invoking external scripts.

---

## Installation

Install the package via Composer:

```bash
composer require voral/laravel-version-increment --dev
```

> The package is auto-registered thanks to Laravel Package Auto-Discovery.

---

## Usage

After installation, the following Artisan commands become available.

### Increment the version:

```bash
# Automatically detect the release type (based on Conventional Commits)
php artisan vs-version:increment

# Explicitly specify the version type
php artisan vs-version:increment major
php artisan vs-version:increment minor
php artisan vs-version:increment patch
```

### Preview changes that would be included in the next version and the expected new version — without modifying any files:

```bash
# Automatically detect the release type
php artisan vs-version:debug

# Explicitly specify the version type
php artisan vs-version:debug major
php artisan vs-version:debug minor
php artisan vs-version:debug patch
```

### Update `CHANGELOG.md` and `composer.json` (if configured), but skip creating the final Git commit and tag:

```bash
# Automatically detect the release type
php artisan vs-version:no-commit

# Explicitly specify the version type
php artisan vs-version:no-commit major
php artisan vs-version:no-commit minor
php artisan vs-version:no-commit patch
```

### List all registered commit types and scopes:

```bash
php artisan vs-version:list
```

---

## Requirements

- PHP 8.2+
- Laravel 11 or 12
- Git available in `PATH`
- `voral/version-increment` (installed automatically as a dependency)

---

## Configuration

The adapter uses **the same configuration file** as the original CLI tool.  
Create `.vs-version-increment.php` in your project root to customize:

- rules for determining major/minor/patch increments,
- `CHANGELOG.md` formatting,
- ignoring untracked files,
- handling squashed commits,
- custom commit types, and more.

See the full
documentation: [vs-version-incrementor Configuration Guide](https://github.com/Voral/vs-version-incrementor?tab=readme-ov-file#configuration)

## Implementation Details

This package is a **thin wrapper** around the original utility: it invokes `./vendor/bin/vs-version-increment` with the
appropriate arguments and flags, ensuring **full behavioral parity** with the standalone CLI tool.

---

## License

MIT. See [LICENSE](LICENSE) for details.

> **Depends on**: [vs-version-incrementor](https://github.com/Voral/vs-version-incrementor) — automated versioning based
> on Git history.
