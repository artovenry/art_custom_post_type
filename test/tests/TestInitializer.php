<?
class TestInitializer extends PHPUnit_Framework_TestCase{
  function testRegisteration(){
    $this->assertTrue(post_type_exists("event"), "'event' post type must exists.");
    $event= get_post_type_object("event");
    $this->assertEquals($event->labels->name, Event::$post_type_options["label"]);
    $this->assertEquals(get_post_type_supports("event"), Event::$default_post_type_options["supports"]);
  }
}
