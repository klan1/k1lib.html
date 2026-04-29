<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("05 - Inline Tags");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container mt-4");
$body->append_h1("Inline Tags Example");

$link = new \k1lib\html\a("https://klan1.com", "Klan1 Labs");
$strong = new \k1lib\html\strong("important");
$em = new \k1lib\html\em("emphasized");
$code = new \k1lib\html\code("echo 'Hello';");

$p1 = $body->append_p();
$p1->set_value("Visit our website at $link for more information.");

$p2 = $body->append_p();
$p2->set_value("This is $strong and this is $em.");

$p3 = $body->append_p();
$p3->set_value("Run $code in your terminal.");

echo $doc->generate();
