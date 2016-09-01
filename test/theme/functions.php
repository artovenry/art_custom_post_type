<?
require __DIR__ . "/custom_post_types/event.php";
require __DIR__ . "/custom_post_types/type_one.php";
require __DIR__ . "/custom_post_types/type_two.php";
Event::initialize();
TypeOne::initialize();
TypeTwo::initialize();
function custom_theme_function(){}
