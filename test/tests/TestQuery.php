<?
class TestQuery extends  Artovenry\CustomPostType\TestCase{
  function setup(){
    global $wpdb;
    $wpdb->query("truncate table {$wpdb->posts}");
    $wpdb->query("truncate table {$wpdb->postmeta}");
  }

  function test_build(){
    $one= $this->insert("type_one");
    $two= $this->insert("type_two");
    try{
      TypeOne::build($two);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotFound,
      "Build needs a post or post_id with its post_type"
      );
    }
  }
}
