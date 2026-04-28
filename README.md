# k1lib.html

A comprehensive PHP library for generating HTML documents and components using an object-oriented approach. This package is a modular extraction of the HTML generation tools from the main `k1lib` library.

## Features

- **DOM-like Structure**: Create HTML documents by nesting objects, mirroring the natural structure of HTML.
- **Comprehensive Tag Support**: Dedicated classes for almost all standard HTML tags (`div`, `table`, `form`, `input`, `p`, etc.). Recently expanded to include full Semantic Layout, Text Formatting, Table and List tags.

- **Framework Integrations**: Built-in support for Bootstrap specialized components and methods for layouts.
- **Flexible Attributes**: Easily set and modify HTML attributes through a fluent API.
- **Component-Based**: Includes reusable components like accordions, modals, and grids.

## Installation

Install via Composer:

```bash
composer require klan1/k1lib.html
```

## Quick Start

```php
use k1lib\html\html_document;
use k1lib\html\body;
use k1lib\html\div;

$doc = new html_document();
$body = $doc->append_body();
$div = $body->append_div("main-container");
$div->set_value("Hello, k1lib.html!");

echo $doc->generate();
```

## License

This project is licensed under the Apache-2.0 License.

---
Alejandro Trujillo Jiménez
**Klan1 Labs - R&D Team**
Website: **[klan1.com](https://klan1.com)**
