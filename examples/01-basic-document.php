<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("01 - Basic Document");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->append_h1("Basic Document Example");
$body->append_p("This is a minimal HTML document created with k1lib.html.");
$body->append_div("alert alert-info")->set_value("You can nest tags and use the fluent API.");

echo $doc->generate();
