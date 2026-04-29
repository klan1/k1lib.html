<?php

require_once __DIR__ . '/../vendor/autoload.php';

use k1lib\html\html_document;

$doc = new html_document();
$doc->head()->set_title("Mazer Dashboard - k1lib.html");
$doc->head()->append_meta("charset", "UTF-8");
$doc->head()->append_meta("viewport", "width=device-width, initial-scale=1.0");

$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");
$doc->head()->link_css("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");

$body = $doc->body();

$app = $body->append_div("d-flex");
$app->set_id("app");

$sidebar = $app->append_div("sidebar-wrapper active");
$sidebar->set_id("sidebar");

$sidebarHeader = $sidebar->append_div("sidebar-header position-relative");
$headerInner = $sidebarHeader->append_div("d-flex justify-content-between align-items-center");
$logo = $headerInner->append_div("logo");
$logoLink = $logo->append_child(new \k1lib\html\a("index.html", "Mazer", null, null, null));

$menuTitle = $sidebar->append_child(new \k1lib\html\li("Menu"));
$menuTitle->set_class("sidebar-title");

$menuUl = $sidebar->append_ul("menu");

$dashboardItem = $menuUl->append_li();
$dashboardItem->set_class("sidebar-item active");
$dashboardLink = $dashboardItem->append_child(new \k1lib\html\a("index.html", "Dashboard"));
$dashboardLink->set_class("sidebar-link");
$dashboardLink->append_i(null, "bi bi-grid-fill");
$dashboardLink->append_span("Dashboard")->set_class("ms-2");

$componentsItem = $menuUl->append_li();
$componentsItem->set_class("sidebar-item has-sub");
$componentsLink = $componentsItem->append_child(new \k1lib\html\a("#", "Components"));
$componentsLink->set_class("sidebar-link");
$componentsLink->append_i(null, "bi bi-stack");
$componentsLink->append_span("Components")->set_class("ms-2");

$submenu = $componentsItem->append_ul("submenu");
$submenu->append_li()->append_child(new \k1lib\html\a("component-accordion.html", "Accordion"))->set_class("submenu-link");
$submenu->append_li()->append_child(new \k1lib\html\a("component-alert.html", "Alert"))->set_class("submenu-link");
$submenu->append_li()->append_child(new \k1lib\html\a("component-badge.html", "Badge"))->set_class("submenu-link");
$submenu->append_li()->append_child(new \k1lib\html\a("component-button.html", "Button"))->set_class("submenu-link");

$main = $app->append_child(new \k1lib\html\main());
$main->set_id("main");
$main->set_class("flex-grow-1 p-4");

$pageTitle = $main->append_h1("Dashboard")->set_class("mb-4");

$statsRow = $main->append_div("row");

$statCards = [
    ["icon" => "bi bi-people-fill", "label" => "Profile Views", "value" => "112.000", "color" => "blue"],
    ["icon" => "bi bi-heart-fill", "label" => "Likes", "value" => "183.000", "color" => "purple"],
    ["icon" => "bi bi-person-plus-fill", "label" => "Following", "value" => "80.000", "color" => "green"],
    ["icon" => "bi bi-bookmark-fill", "label" => "Saved Post", "value" => "112", "color" => "red"],
];

foreach ($statCards as $card) {
    $col = $statsRow->append_div("col-6 col-lg-3 col-md-6");
    $cardDiv = $col->append_div("card");
    $cardBody = $cardDiv->append_div("card-body px-4 py-4-5");
    $innerRow = $cardBody->append_div("row");

    $iconCol = $innerRow->append_div("col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start");
    $iconBox = $iconCol->append_div("stats-icon {$card['color']} mb-2");
    $iconBox->append_i(null, $card['icon']);

    $textCol = $innerRow->append_div("col-md-8 col-lg-12 col-xl-12 col-xxl-7");
    $textCol->append_h6($card['label'])->set_class("text-muted font-semibold");
    $textCol->append_h6($card['value'])->set_class("font-extrabold mb-0");
}

$chartRow = $main->append_div("row mt-4");
$chartCol = $chartRow->append_div("col-12");
$chartCard = $chartCol->append_div("card");
$chartCard->append_div("card-header")->append_h4("Profile Visit")->set_class("mb-0");
$chartCardBody = $chartCard->append_div("card-body");
$chartCardBody->append_div("")->set_id("chart-profile-visit");

$tableRow = $main->append_div("row mt-4");
$tableCol = $tableRow->append_div("col-12");
$tableCard = $tableCol->append_div("card");
$tableCard->append_div("card-header")->append_h4("Latest Comments")->set_class("mb-0");

$tableBody = $tableCard->append_div("card-body");
$tableResponsive = $tableBody->append_div("table-responsive");

$table = $tableResponsive->append_child(new \k1lib\html\table("table table-hover table-lg"));
$tableHead = $table->append_thead();
$tableHeaderRow = $tableHead->append_tr();
$tableHeaderRow->append_child(new \k1lib\html\th("Name"));
$tableHeaderRow->append_child(new \k1lib\html\th("Comment"));

$tableBody = $table->append_tbody();

$comments = [
    ["name" => "Si Cantik", "comment" => "Congratulations on your graduation!"],
    ["name" => "Si Ganteng", "comment" => "Wow amazing design! Can you make another tutorial for this design?"],
    ["name" => "Singh Eknoor", "comment" => "What a stunning design! You are so talented and creative!"],
    ["name" => "Rani Jhadav", "comment" => "I love your design! It's so beautiful and unique!"],
];

foreach ($comments as $row) {
    $tr = $tableBody->append_tr();
    $nameTd = $tr->append_td("");
    $nameTd->set_class("col-3");
    $nameDiv = $nameTd->append_div("d-flex align-items-center");
    $nameDiv->append_div("avatar avatar-md")->append_child(new \k1lib\html\img("./assets/compiled/jpg/5.jpg", "Avatar"))->set_class("rounded-circle");
    $nameDiv->append_p($row['name'])->set_class("font-bold ms-3 mb-0");

    $commentTd = $tr->append_td("");
    $commentTd->set_class("col-auto");
    $commentTd->append_p($row['comment'])->set_class("mb-0");
}

$footer = $app->append_child(new \k1lib\html\footer());
$footer->set_class("footer mt-auto py-3 bg-light");
$footerContainer = $footer->append_div("container text-center");
$footerContainer->append_span("2026 © Mazer. Generated with k1lib.html.")->set_class("text-muted");

echo $doc->generate();
