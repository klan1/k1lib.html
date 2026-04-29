<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("02 - Semantic Layout");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container");

$header = $body->append_child(new \k1lib\html\header());
$header->set_class("bg-primary text-white p-3 mb-3");
$header->append_h2("Site Header")->set_class("m-0");

$main = $body->append_child(new \k1lib\html\main());
$main->set_class("row");

$article = $main->append_child(new \k1lib\html\article());
$article->set_class("col-md-8");
$article->append_h3("Main Article");
$article->append_p("This is the main content area using semantic HTML5 tags.");

$aside = $main->append_child(new \k1lib\html\aside());
$aside->set_class("col-md-4");
$aside->append_h4("Sidebar");
$aside->append_p("Related content goes here.");

$footer = $body->append_child(new \k1lib\html\footer());
$footer->set_class("bg-dark text-white p-3 mt-3");
$footer->append_p("&copy; 2026 k1lib.html");

echo $doc->generate();
