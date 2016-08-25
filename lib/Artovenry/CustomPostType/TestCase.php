<?
namespace Artovenry\CustomPostType;

class TestCase extends \PHPUnit_Framework_TestCase{
  function setup(){
    global $wpdb;
    $wpdb->query("truncate table {$wpdb->posts}");
    $wpdb->query("truncate table {$wpdb->postmeta}");
    $this->one= $this->insert("type_one", "Daikon");
    $this->two= $this->insert("type_two");
  }
  function assert($arg, $message=""){
    return $this->assertTrue($arg, $message);
  }
  function insert($post_type, $title=""){
    return wp_insert_post([
    "post_title"    =>$title,
    "post_type"     =>$post_type,
    "post_content"  =>"post_content",
    "post_author"   =>1,
    "post_status"   =>"publish",
    ]);
  }
}
