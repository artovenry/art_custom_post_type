<?
namespace Artovenry\CustomPostType;

class TestCase extends \PHPUnit_Framework_TestCase{
  function assert($var, $message=""){
    return $this->assertTrue($var, $message);
  }
  function assertNot($var, $message=""){
    return $this->assert(!$var, $message);
  }
  function insert($post_type, $title=""){
    wp_insert_post([
    "post_title"    =>$title,
    "post_type"     =>$post_type,
    "post_content"  =>"post_content",
    "post_author"   =>1,
    "post_status"   =>"publish",
    ]);
  }
}
