<?
class TestInitializer extends PHPUnit_Framework_TestCase{
  function testRegisteration(){
    $event= get_post_type_object("event");

    $this->assertTrue(post_type_exists("event"), "'event' post type must exists.");

    $this->assertEquals($event->labels->name, Event::$post_type_options["label"]);

    $this->assertEquals($event->support, Event::$default_post_type_options["support"]);
  }
}
