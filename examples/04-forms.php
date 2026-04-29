<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("04 - Forms");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");

$body = $doc->body();
$body->set_class("container mt-4");
$body->append_h1("Form Example");

$form = $body->append_child(new \k1lib\html\form("contact-form"));
$form->set_class("row g-3");

$nameDiv = $form->append_div("col-md-6");
$nameDiv->append_child(new \k1lib\html\label("Name", "name"));
$nameDiv->append_child(new \k1lib\html\input("text", "name", "", "form-control"));

$emailDiv = $form->append_div("col-md-6");
$emailDiv->append_child(new \k1lib\html\label("Email", "email"));
$emailDiv->append_child(new \k1lib\html\input("email", "email", "", "form-control"));

$msgDiv = $form->append_div("col-12");
$msgDiv->append_child(new \k1lib\html\label("Message", "message"));
$textarea = $msgDiv->append_child(new \k1lib\html\textarea("message", "form-control"));
$textarea->set_value("Enter your message here...");

$selectDiv = $form->append_div("col-md-6");
$selectDiv->append_child(new \k1lib\html\label("Priority", "priority"));
$select = $selectDiv->append_child(new \k1lib\html\select("priority", "form-select"));
$select->append_option("low", "Low");
$select->append_option("medium", "Medium", true);
$select->append_option("high", "High");

$btnDiv = $form->append_div("col-12");
$btnDiv->append_child(new \k1lib\html\button("Send", "btn btn-primary", "submit-btn", "submit"));

echo $doc->generate();
