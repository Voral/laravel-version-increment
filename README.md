# Laravel Adapter for `vs-version-incrementor`

Этот пакет предоставляет **нативную Artisan-команду** для [**`vs-version-incrementor`**](https://github.com/Voral/vs-version-incrementor) — инструмента автоматического управления версиями и генерации `CHANGELOG.md` на основе анализа Git-коммитов.

Теперь вы можете использовать все возможности `vs-version-incrementor` прямо из Laravel-консоли — без вызова внешних скриптов.

---

## Установка

Установите пакет через Composer:

```bash
composer require voral/laravel-version-increment --dev
```

> Пакет автоматически регистрируется благодаря Laravel Package Auto-Discovery.

---

## Использование

После установки доступна команда:

```bash
php artisan vs-version:increment
```

### Основные варианты вызова

```bash
# Автоматическое определение типа релиза (по Conventional Commits)
php artisan vs-version:increment

# Явное указание типа версии
php artisan vs-version:increment major
php artisan vs-version:increment minor
php artisan vs-version:increment patch

# Просмотр изменений без применения (--debug)
php artisan vs-version:increment --debug

# Применить изменения, но не создавать Git-коммит и тег (--no-commit)
php artisan vs-version:increment --no-commit

# Показать зарегистрированные типы коммитов и scope'ы
php artisan vs-version:increment --list
```

Все опции полностью соответствуют оригинальной утилите `vs-version-incrementor`.

---

## Требования

- PHP 8.1+
- Laravel 11 или 12
- Git в `PATH`
- Установленный `voral/version-increment` (устанавливается автоматически)

---

## Конфигурация

Пакет использует **ту же конфигурацию**, что и оригинальная утилита.  
Создайте файл `.vs-version-increment.php` в корне проекта, чтобы настроить:

- правила определения major/minor/patch,
- формат CHANGELOG,
- игнорирование untracked-файлов,
- обработку squashed-коммитов,
- кастомные типы коммитов и многое другое.

Подробнее: [Документация vs-version-incrementor](https://github.com/Voral/vs-version-incrementor#%D0%9A%D0%BE%D0%BD%D1%84%D0%B8%D0%B3%D1%83%D1%80%D0%B0%D1%86%D0%B8%D1%8F)

---

## События и расширяемость

Утилита поддерживает **событийную модель через `EventBus`**, что позволяет:

- слушать события `BEFORE_VERSION_SET`, `AFTER_VERSION_SET_SUCCESS`, `ON_ERROR`,
- отправлять уведомления в Slack/Telegram,
- логировать операции,
- интегрировать с внешними системами.

События работают **точно так же**, как в CLI-версии — без изменений.

---

## Разработка

Пакет является **тонкой обёрткой** над оригинальной утилитой: он вызывает `./vendor/bin/vs-version-increment` с передачей всех аргументов и флагов, обеспечивая **полное поведенческое соответствие**.

---

## Лицензия

MIT. Смотрите [LICENSE](LICENSE) для подробностей.

> **Зависит от**: [vs-version-incrementor](https://github.com/Voral/vs-version-incrementor) — автоматическое управление версиями по Git-истории.

