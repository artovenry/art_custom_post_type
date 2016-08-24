<?
class TestQuery extends  Artovenry\CustomPostType\TestCase{
  function setup(){
    global $wpdb;
    $wpdb->query("truncate table {$wpdb->posts}");
    $wpdb->query("truncate table {$wpdb->postmeta}");
    $this->one= $this->insert("type_one", "Daikon");
    $this->two= $this->insert("type_two");
  }

  function test_accessor(){
    $one= TypeOne::build(get_post($this->one));
    $this->assert($one->ID === $this->one);
    $this->assert($one->post_content === "post_content");
    $this->assert($one->post instanceof WP_Post);
  }

  function test_build(){

    $this->assert(TypeOne::build($this->one) instanceof TypeOne);
    $this->assert(TypeOne::build(get_post($this->one)) instanceof TypeOne);

    try{TypeOne::build(999999);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotFound,
      "Base::build needs a exising post"
      );
    }

    try{TypeOne::build((object)[]);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotWpPost,
      "Base::build needs a valid WP_Post"
      );
    }

    try{TypeOne::build($this->two);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordTypeMismatch,
      "Base::build needs a post or post_id with its post_type"
      );
    }
  }
  function test_find(){
    $id= $this->one;
    $this->assert(TypeOne::find($id)->post_title === "Daikon");

    $this->assert(TypeOne::find(999) === false, "Base::find needs a existing post_id");
    try{$this->assert(TypeOne::find(999, raise));
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\RecordNotFound,
      "Base::find needs a existing post_id"
      );
    }
  }
  function test_take_simple(){
    $this->assert(TypeOne::take() instanceof TypeOne);
    $this->assert(TypeOne::take(2) instanceof TypeOne);
    $this->insert("type_one", "Ninjin");
    $this->assert(TypeOne::take()->post_title === "Ninjin");
    $this->assert(count(TypeOne::take(2)) === 2);
    $this->assert(TypeOne::take(2)[1]->post_title === "Daikon");
  }
  function test_ordering_query(){
    $this->insert("type_one", "Ninjin");
    $this->insert("type_one", "Nasu");
    $this->insert("type_one", "Tamanegi");
    $yasais_4= TypeOne::fetch(["orderby"=>"title", "order"=>"ASC"]);

    $this->assert($yasais_4[0]->post_title === "Daikon");
    $this->assert($yasais_4[1]->post_title === "Nasu");
    $this->assert($yasais_4[2]->post_title === "Ninjin");
    $this->assert($yasais_4[3]->post_title === "Tamanegi");

  }
}
