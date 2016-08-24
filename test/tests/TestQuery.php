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

    $this->assert(TypeOne::build($one) instanceof TypeOne);
    $this->assert(TypeOne::build(get_post($one)) instanceof TypeOne);

    try{TypeOne::build(999999);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotFound,
      "Build needs a exising post"
      );
    }

    try{TypeOne::build((object)[]);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotWpPost,
      "Build needs a valid WP_Post"
      );
    }

    try{TypeOne::build($two);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordTypeMismatch,
      "Build needs a post or post_id with its post_type"
      );
    }
  }
}
