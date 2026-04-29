<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;
use k1lib\html\bootstrap\grid;

$doc = new html_document();
$doc->head()->set_title("06 - Bootstrap Grid");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container mt-4");
$body->append_h1("Bootstrap Grid Example");

$grid = new grid(2, 3);

$grid->row(1)->cell(1)->set_value("Row 1, Col 1");
$grid->row(1)->cell(2)->set_value("Row 1, Col 2");
$grid->row(1)->cell(3)->set_value("Row 1, Col 3");

$grid->row(2)->cell(1)->set_value("Row 2, Col 1");
$grid->row(2)->cell(2)->set_value("Row 2, Col 2");
$grid->row(2)->cell(3)->set_value("Row 2, Col 3");

$body->append_child($grid);

echo $doc->generate();
