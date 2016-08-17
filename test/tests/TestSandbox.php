<?
class TestSandbox extends PHPUnit_Framework_TestCase{
  function test_wp(){
    $this->assertTrue(function_exists("wp_head"));
  }
}
