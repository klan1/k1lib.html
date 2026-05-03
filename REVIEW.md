# Code Review Report — k1lib.html

Complete audit of all 74 PHP source files in `src/klan1/html/`. Issues grouped by severity.

---

## CRITICAL — Will Cause Runtime Errors

### C1. `tag::get_tag_id()` does not return on failure path
**File:** `tag.php:160-166`
```php
function get_tag_id() {
    if (tag_catalog::index_exist($this->tag_id)) {
        return $this->tag_id;
      } else {
        NULL;    // ← NOT a return statement, just a bare expression
      }
}
```
This returns `null` implicitly, but the `NULL;` line is a no-op statement — not a return. PHP allows this but it's misleading and may cause undefined behavior in strict contexts. Should be `return null;`.

### C2. `tag_catalog::is_cataloged()` does not actually check the catalog
**File:** `tag_catalog.php:80-86`
```php
static function is_cataloged($tag_index) {
    if (is_object($tag_index) && method_exists($tag_index, "get_tag_id")) {
        return true;     // ← returns true for ANY object with get_tag_id()
      } else {
        return false;
      }
}
```
Does not call `index_exist()` to verify the tag is actually in the catalog. Returns `true` for any object that happens to have a `get_tag_id` method, even if it was decataloged. Should call `index_exist($tag_index->get_tag_id())`.

### C3. `table_from_data` imports nonexistent `K1MAGIC` class
**File:** `bootstrap/table_from_data.php:7`
```php
use k1lib\K1MAGIC;
```
`K1MAGIC::get_value()` is called on lines 237 and 262. This class does not exist in this project or any declared dependency. Using the `--authcode--` feature in `parse_string_value()` causes a **fatal error**.

### C4. `template::error_500()` calls `DOM::start()` without argument
**File:** `template.php:46`
```php
DOM::start();
DOM::html_document()->body()->append_h1('500 Internal error');
```
`DOM::start(html_document $tpl)` requires an `html_document` argument, but none is passed. Calling `DOM::html_document()` returns `null`, causing a **fatal error** on the next line.

### C5. `img` tag uses bare `TRUE` instead of `IS_SELF_CLOSED` constant
**File:** `img.php:10`
```php
parent::__construct("img", TRUE);
```
Every other self-closed tag (`br`, `hr`, `input`, `meta`) uses `IS_SELF_CLOSED`. `img` uses bare `TRUE`. Inconsistent with the project's own convention.

### C6. `link` tag passes `IS_SELF_CLOSED` (TRUE) but `<link>` is a void element
**File:** `link.php:10`
```php
parent::__construct("link");   // defaults to IS_SELF_CLOSED=TRUE
```
`<link>` is a void element (`<link href="...">`), not `<link></link>`. The constant is semantically correct (it's self-closed), but the `generate()` method treats self-closed tags by omitting the closing tag without adding a `/`. Output is valid HTML5 but the intent expressed by the constant is misleading for developers who expect XML-style self-closing.

---

## HIGH — Logical Bugs

### H1. `table_from_data::use_data()` skips first data row's value parsing
**File:** `bootstrap/table_from_data.php:95`
```php
if ($this->has_header && $row !== 0) {
      $col_value = $this->parse_string_value($col_value, $row);
}
```
`$row` is initialized to `0` on line 81 and incremented on line 131 (after the inner loop). The condition `$row !== 0` is true for all iterations except the first. But `$row` and `$row_index` (the actual data row index) diverge because `$row` is incremented after the loop body. This causes the first data row (index 1) to skip template variable parsing in `parse_string_value()`.

### H2. `tag::get_elements_by_class()` uses substring match, not word boundary
**File:** `tag.php:1049`
```php
if (strstr($this->get_attribute("class"), $class_name) !== FALSE) {
```
`strstr` does a substring search. Searching for class `"btn"` also matches `"btn-group"`, `"my-btn-class"`, etc. Should split the class attribute by spaces and do an exact match per token.

### H3. `tag::generate()` is not idempotent
**File:** `tag.php:705-752`
```php
$this->childs = $this->get_all_childs();
```
Every call to `generate()` merges `childs_head`, `childs`, `childs_tail` into `childs`. Calling `generate()` twice destroys the separation — the second call renders head and tail children as main children again, doubling them in the output.

### H4. `tag::get_elements_by_tag()` has always-true condition (4 occurrences)
**File:** `tag.php:892`, `tag.php:949`, `tag.php:1029`, `tag.php:1079`
```php
if (is_array($tags) || count($tags > 0)) {
    return $tags;
} else {
    return null;
}
```
`$tags`/`$classes` is initialized as `[]`, so `is_array()` is always `true`. The `else` branch is dead code. Should be `return count($tags) > 0 ? $tags : null;`.

### H5. `li::append_ol()` creates a `ul`, not an `ol`
**File:** `li.php:38`
```php
/**
 * @return ul
 */
function append_ol($class = NULL, $id = NULL) {
      $new = new ul($class, $id);   // ← creates ul, not ol!
```
The method is named `append_ol` but creates a `ul` object. Copy-paste bug — should be `new ol($class, $id)` and `@return ol`.

### H6. `ol::append_li()` sets value on the OL, not the LI
**File:** `ol.php:28-32`
```php
function append_li($value = NULL, $class = NULL, $id = NULL) {
      $new = new li($value, $class, $id);
      $this->set_value($value);     // ← sets value on the OL itself!
      $this->append_child($new);
    return $new;
}
```
`$this->set_value($value)` puts the value on the `<ol>` element. The `li` constructor already receives `$value`, so this line is either redundant or a bug (pollutes the OL's value).

### H7. `ul::append_li()` and `ol::append_li()` behave differently
`ul::append_li()` does NOT call `$this->set_value()`. `ol::append_li()` does. Same operation, different behavior between parent classes.

---

## HIGH — Fluent API Inconsistencies

The following setter methods do NOT return `$this`, breaking method chaining:

| File | Method | Line |
|------|--------|------|
| `tag.php` | `set_use_log($use_log)` | 176 |
| `tag.php` | `set_parent(tag $parent)` | 192 |
| `tag.php` | `set_id($id, $append)` | 545 — only returns `$this` when `$id` is not empty |
| `head.php` | `set_title($document_title)` | 33 |

Setters that DO return `$this` (correct pattern): `set_value`, `set_attrib`, `set_class`, `set_style`, `a::set_href`, `img::set_src`, `img::set_alt`, `iframe::set_value`, `link::set_value`.

---

## MEDIUM — Inconsistent Constructor Patterns

### M1. `section` has reversed parameter order vs every other tag
**File:** `section.php:10`
```php
function __construct($id = NULL, $class = NULL) {
```
vs every other tag (`div`, `span`, `h1`-`h6`, `p`, `ul`, `li`, etc.):
```php
function __construct($class = NULL, $id = NULL) {
```

### M2. `strong` has empty string default, others use `NULL`
**File:** `strong.php:13`
```php
function __construct($value = '', $class = NULL, $id = NULL) {
```
All other text tags (`h1`-`h6`, `p`, `i`, `em`, `b`) use `$value = NULL`.

### M3. `pre` requires `$value` parameter, `code` does not
**File:** `pre.php:11` vs `code.php:11`
```php
// pre: required $value
function __construct($value, $class = NULL, $id = NULL) {
// code: no $value parameter at all
function __construct($class = NULL, $id = NULL) {
```
Both are formatting tags with the same semantic purpose but different APIs.

### M4. `legend` has no `$class` or `$id` parameters
**File:** `legend.php:12`
```php
function __construct($value) {
```
Other text-containing tags accept `$class` and `$id`.

---

## MEDIUM — Naming Typos

### N1. `append_shotcuts` → should be `append_shortcuts`
**File:** `append_shotcuts.php` — used in 40+ files. The trait name is misspelled.

### N2. `childs` / `childs_head` / `childs_tail` → should be `children`
**File:** `tag.php` — property names throughout. Non-standard English.

### N3. `has_childs()` → should be `has_children()`
**File:** `tag.php:1090`

### N4. `targuet` → should be `target`
**File:** `select.php:39`
```php
$targuet_tag = $this->get_elements_by_attrib_value("value", $value);
```

---

## MEDIUM — Docblock Issues

### D1. Wrong class names in docblocks
| File | Docblock Says | Actual Class |
|------|---------------|--------------|
| `fieldset.php:6` | `P` | `fieldset` |
| `nav.php:5` | `NAV` | `nav` |
| `style.php:11` | `SCRIPT html tag` | `style` |
| `ol.php:11` | `Create a UL html tag` | `ol` (copy-paste from ul.php) |

### D2. Misleading `li` constructor docblock
**File:** `li.php:13-15`
```php
/**
 * Create a LI html tag with VALUE as data.
 * @param string $class
 */
function __construct($value = NULL, $class = NULL, $id = NULL) {
```
First `@param` says `$class` but the first parameter after the description is `$value`. Docblock params are in wrong order.

---

## MEDIUM — Dead Code / Commented-Out Code

### E1. Commented-out code in `tag.php`
- Line 110: `// $this->set_attrib("class", "k1lib-{$tag_name}-object");`
- Lines 216-223: Alternative `__toString()` implementation
- Line 489: `// echo "$n exists ! ..."`
- Line 745: `// TODO: Fix this!! please no more pre_code and post_code`

### E2. Commented-out code in `template.php`
Lines 15-17: `$js_path`, `$css_path`, `$images_path`
Lines 22-37: Full `enable()` method with path parameters

### E3. Commented-out code in `ol.php` and `ul.php`
- `ol.php:18`: `// $this->data_array &= $data_array;`
- `ul.php:18`: `// $this->data_array &= $data_array;`
- `table.php:10`: `// private $data_array = array();`

### E4. `DOM::link_html()` throws an error on purpose
**File:** `DOM.php:52-54`
```php
static function link_html(html $html_to_link) {
    trigger_error('Do no do this ' . __METHOD__ . ' at ' . __CLASS__, E_USER_ERROR);
}
```
Typo: "Do no do" → "Do not do". Method should be removed or implemented.

---

## LOW — Design Issues

### G1. `tag::$root` is static — library is non-reentrant
**File:** `tag.php:27`
```php
static tag|null $root = null;
```
Only one `html_document` can exist per PHP process. Creating two documents overwrites `$root`.

### G2. `tag_log::get_log()` applies `htmlspecialchars`
**File:** `tag_log.php:20`
```php
static function get_log() {
    return htmlspecialchars(self::$log);
}
```
Escapes for HTML output. Useless for non-HTML contexts. Should provide raw + escaped variants.

### G3. `head::set_title()` assumes `$this->title` exists
**File:** `head.php:33-34`
```php
function set_title($document_title) {
      $this->title->set_value($document_title);
}
```
If `html_document` was constructed with `$default_head = true`, `append_title()` was never called and `$this->title` is `null`. Fatal error.

### G4. No namespace for constants — global pollution
**File:** `tag.php:5-17`
Constants (`IS_SELF_CLOSED`, `NO_CLASS`, `APPEND_ON_HEAD`, etc.) are defined at file scope within the namespace block. They are namespace-scoped but look like globals. Should be class constants for clarity.

### G5. Inconsistent use of `IS_SELF_CLOSED` vs bare `TRUE`/`FALSE`
Most tags use `IS_SELF_CLOSED`/`IS_NOT_SELF_CLOSED` constants. Some (`img`, `i`, `b`, `em`, `code`, `strong`, `span`, `footer`, `main`, `article`, `aside`, `caption`, `tfoot`) use bare `TRUE`/`FALSE` or `IS_NOT_SELF_CLOSED` inconsistently.

### G6. `decatalog()` does not handle `childs_head` or `childs_tail`
**File:** `tag.php:133-149`
```php
function decatalog() {
    tag_catalog::decatalog($this->tag_id);
    if ($this->has_child) {
        foreach ($this->childs as $child_object) {
              $child_object->decatalog();
          }
    }
    foreach ($this->get_inline_tags() as $tag) {
          $tag->decatalog();
    }
}
```
Only iterates `$this->childs`. Does not decatalog children in `$this->childs_head` or `$this->childs_tail`. Those children remain in the catalog orphaned.

### G7. `remove_childs()` uses `unset()` on array elements, not `array_values()` reindex
**File:** `tag.php:303-318`
```php
foreach ($this->childs as $key => $child) {
    unset($this->childs[$key]);
```
`unset()` on array keys leaves gaps (e.g., `[0, 2, 5]` after removing index 1). Subsequent `get_child($n)` calls may return `FALSE` for indices that still exist.

---

## SUMMARY

| Category | Count |
|----------|-------|
| Critical (runtime errors) | 6 |
| High (logical bugs) | 7 |
| High (fluent API violations) | 4 methods |
| Medium (constructor inconsistency) | 4 |
| Medium (naming typos) | 4 |
| Medium (docblock issues) | 2 |
| Medium (dead code) | 4 |
| Low (design issues) | 7 |
| **Total** | **38** |
