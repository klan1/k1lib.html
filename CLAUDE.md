# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

k1lib.html is a PHP library (PHP 8.2+) that generates HTML documents using an object-oriented, DOM-like approach. Every HTML tag is represented by a PHP object that can be nested, queried, and rendered to a string.

## Architecture

### Core Classes

- **`tag`** (`src/klan1/html/tag.php`): The base class for all HTML elements. Manages attributes, child collections, inline tag resolution, and HTML generation. Every tag instance is registered in a global catalog (`tag_catalog`) and receives a unique ID.
- **`html_document`** (`src/klan1/html/html_document.php`): Extends `tag`. Represents the root `<html>` element and automatically creates `<head>` and `<body>` children. Sets `tag::$root` to itself.
- **`DOM`** (`src/klan1/html/DOM.php`): Static compatibility wrapper around `html_document` for k1lib v1 code.
- **`tag_catalog`** (`src/klan1/html/tag_catalog.php`): Static registry that assigns unique IDs to all `tag` instances. Enables inline tag embedding and object lookup by ID.
- **`tag_log`** (`src/klan1/html/tag_log.php`): Optional global action logger. Enabled via `html_document::set_use_log(true)`.
- **`append_shotcuts`** (`src/klan1/html/append_shotcuts.php`): Trait that provides fluent `append_div()`, `append_p()`, `append_span()`, etc., on any tag.

### Tag Implementations

Each standard HTML tag has its own class file named after the tag (e.g., `div.php`, `input.php`, `table.php`).
- Classes extend `tag` and typically `use append_shotcuts`.
- The constructor calls `parent::__construct($tag_name, $self_closed)` where `$self_closed` is a boolean.
- Namespace is `k1lib\html`.

### Bootstrap Components

Located under `src/klan1/html/bootstrap/` with namespace `k1lib\html\bootstrap`.
- **`bootstrap_methods`** trait: Adds grid sizing helpers (`small()`, `medium()`, `large()`, `xlarge()`, `xxlarge()`, `general()`) and alignment methods.
- Components include `grid`, `modal`, `accordion`, `menu`, `table_from_data`, `top_bar`, etc.
- Grid classes (`grid`, `grid_row`, `grid_cell`) build Bootstrap grid structures programmatically.

### Notifications System

`src/klan1/html/notifications/common_code.php` and `on_DOM.php` provide a session-based message queue that inserts alert messages into the DOM at render time.

### Template System

`template` (`src/klan1/html/template.php`) is a static class for loading PHP template files from a configured directory.

## Key Patterns

### Inline Tags
When a tag object is used as a string (e.g., interpolated into text), `__toString()` returns `{{ID:N}}`. During `generate()`, the parent tag's `parse_value()` replaces these placeholders with the actual rendered HTML. This allows embedding tags inside other tags' text values.

### Child Collections
Tags maintain three child arrays:
- `childs_head` — rendered before the main children
- `childs` — main child collection
- `childs_tail` — rendered after the main children

Use `append_child($tag, $put_last_position, APPEND_ON_HEAD|APPEND_ON_MAIN|APPEND_ON_TAIL)` to control placement.

### Fluent API
Most setter and append methods return `$this` or the newly created child object, enabling method chaining.

### Attribute Shortcuts
- `set_id($id)` — wrapper for `set_attrib("id", $id)`
- `set_class($class)` — wrapper for `set_attrib("class", $class)`; pass `TRUE` as second arg to append instead of overwrite
- `set_style($style)` — wrapper for `set_attrib("style", $style)`

### Constants (namespace `k1lib\html`)
- `IS_SELF_CLOSED` / `IS_NOT_SELF_CLOSED`
- `NO_CLASS`, `NO_ID`, `NO_VALUE`
- `APPEND_ON_HEAD`, `APPEND_ON_MAIN`, `APPEND_ON_TAIL`
- `INSERT_ON_PRE_TAG`, `INSERT_ON_AFTER_TAG_OPEN`, `INSERT_ON_VALUE`, `INSERT_ON_BEFORE_TAG_CLOSE`, `INSERT_ON_POST_TAG`

## Adding a New HTML Tag

1. Create `src/klan1/html/<tagname>.php`.
2. Define `class <tagname> extends tag` in namespace `k1lib\html`.
3. `use append_shotcuts;` if the tag should support fluent child appending.
4. In the constructor, call `parent::__construct("<tagname>", IS_SELF_CLOSED or IS_NOT_SELF_CLOSED)`.
5. Set any default attributes or classes.

## Development

### Installing Dependencies
```bash
composer install
```

### Tests / Linting / CI
No test suite, linting, or CI configuration is currently present in this repository.

### Autoloading
Configured in `composer.json` with PSR-4: `k1lib\html\` maps to `src/klan1/html/`.

## Important Notes

- The `nbproject/` directory contains NetBeans IDE configuration and should not be committed (it is partially ignored by `.gitignore`).
- The `.DS_Store` files are tracked in `.gitignore` but may still appear in the working tree.
- This library has no external Composer dependencies beyond PHP 8.2.
