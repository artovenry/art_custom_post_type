<?
require dirname(__DIR__) . "/vendor/autoload.php";
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header,$current_user;
define('WP_USE_THEMES', false);

$wp_version= getenv("WP_VERSION");
require __DIR__ . "/wp/${wp_version}/wp-blog-header.php";
