<?
require __DIR__ . "/custom_post_types/Event.php";
require __DIR__ . "/custom_post_types/TypeOne.php";
require __DIR__ . "/custom_post_types/TypeTwo.php";
Event::initialize();
TypeOne::initialize();
TypeTwo::initialize();
function custom_theme_function(){}
