<?
class TestSandbox extends PHPUnit_Framework_TestCase{
  function test_wp(){
    $this->assertTrue(function_exists("wp_head"));
    $this->assertTrue(function_exists("custom_theme_function"));
    $this->assertTrue(class_exists("Artovenry\CustomPostType\Base"));

  }
}
