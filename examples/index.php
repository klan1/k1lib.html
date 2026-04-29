<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("k1lib.html Examples");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container mt-4");
$body->append_h1("k1lib.html Examples");
$body->append_p("Select an example below to see the library in action.");

$examples = [
    "01-basic-document.php" => "Basic Document",
    "02-semantic-layout.php" => "Semantic Layout",
    "03-tables.php" => "Tables",
    "04-forms.php" => "Forms",
    "05-inline-tags.php" => "Inline Tags",
    "06-bootstrap-grid.php" => "Bootstrap Grid",
];

$list = $body->append_ul("list-group");
foreach ($examples as $file => $label) {
    $item = $list->append_li();
    $item->set_class("list-group-item");
    $link = $item->append_child(new \k1lib\html\a($file, $label));
    $link->set_class("text-decoration-none");
}

echo $doc->generate();
