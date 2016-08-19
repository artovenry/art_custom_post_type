<?
class TestSandbox extends PHPUnit_Framework_TestCase{
  function test_wp(){
    $this->assertTrue(function_exists("wp_head"));
  }
  function test_wp2(){
    $this->assertTrue(function_exists("has_custom_logo"));
  }
}
