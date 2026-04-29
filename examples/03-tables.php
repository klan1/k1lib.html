<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("03 - Tables");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container mt-4");
$body->append_h1("Table Example");

$table = $body->append_child(new \k1lib\html\table("table table-striped table-bordered"));
$table->set_id("example-table");

$thead = $table->append_thead();
$headerRow = $thead->append_tr();
$headerRow->append_child(new \k1lib\html\th("Name"));
$headerRow->append_child(new \k1lib\html\th("Role"));
$headerRow->append_child(new \k1lib\html\th("Status"));

$tbody = $table->append_tbody();

$rows = [
    ["Alice", "Developer", "Active"],
    ["Bob", "Designer", "Active"],
    ["Charlie", "Manager", "Away"],
];

foreach ($rows as $rowData) {
    $tr = $tbody->append_tr();
    foreach ($rowData as $cellValue) {
        $tr->append_child(new \k1lib\html\td($cellValue));
    }
}

echo $doc->generate();
