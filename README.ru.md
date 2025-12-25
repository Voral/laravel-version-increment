# Laravel Adapter for `vs-version-incrementor`

[EN](https://github.com/Voral/laravel-version-increment/blob/master/README.ru.md)


Этот пакет предоставляет **нативную Artisan-команду** для [**`vs-version-incrementor`
**](https://github.com/Voral/vs-version-incrementor) — инструмента автоматического управления версиями и генерации
`CHANGELOG.md` на основе анализа Git-коммитов.

Теперь вы можете использовать все возможности `vs-version-incrementor` прямо из Laravel-консоли — без вызова внешних
скриптов.

---

## Установка

Установите пакет через Composer:

```bash
composer require voral/laravel-version-increment --dev
```

> Пакет автоматически регистрируется благодаря Laravel Package Auto-Discovery.

---

## Использование

После установки доступен ряд команд.

Инкремент версии:

```bash
# Автоматическое определение типа релиза (по Conventional Commits)
php artisan vs-version:increment

# Явное указание типа версии
php artisan vs-version:increment major
php artisan vs-version:increment minor
php artisan vs-version:increment patch
```

Проверка изменений, которые попадут в следующую версию и предполагаемая следующая версия. Без правки файлов:

```bash
# Автоматическое определение типа релиза (по Conventional Commits)
php artisan vs-version:debug

# Явное указание типа версии
php artisan vs-version:debug major
php artisan vs-version:debug minor
php artisan vs-version:debug patch
```

Изменение файлов CHANGELOG.md и composer.json (если настроено), но без автоматического выполнения окончательного
коммита:

```bash
# Автоматическое определение типа релиза (по Conventional Commits)
php artisan vs-version:no-commit

# Явное указание типа версии
php artisan vs-version:no-commit major
php artisan vs-version:no-commit minor
php artisan vs-version:no-commit patch
```

Список возможных типов коммитов и скоупов:

```bash
php artisan vs-version:list
```

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

Подробнее: [Документация vs-version-incrementor](https://github.com/Voral/vs-version-incrementor/blob/master/README.ru.md#конфигурирование)

---

## Разработка

Пакет является **тонкой обёрткой** над оригинальной утилитой: он вызывает `./vendor/bin/vs-version-increment` с
передачей всех аргументов и флагов, обеспечивая **полное поведенческое соответствие**.

---

## Лицензия

MIT. Смотрите [LICENSE](LICENSE) для подробностей.

> **Зависит от**: [vs-version-incrementor](https://github.com/Voral/vs-version-incrementor) — автоматическое управление
> версиями по Git-истории.

