<?
class TestPostMeta extends  Artovenry\CustomPostType\TestCase{
  function setup(){
    parent::setup();
    update_post_meta($this->one,"art_type_one_show_at_home", 1);
  }
  // function test_accessor(){
  //   $one= TypeOne::build($this->one);
  //   $one->set("show_at_home" , false);
  //   $this->assert($one->show_at_home === (string)false);
  // }

  function test_getter(){
    $one= TypeOne::build($this->one);
    $two= TypeTwo::build($this->two);
    $this->assert($one->show_at_home === "1", "Meta_attributes act as attribute-reader.");
    try{$this->assert($one->unexistent_name, 0);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\AttributeNotFound,
      "Undefined attribute access raises error."
      );
    }
    try{$this->assert($two->unexistent_name, 0);
    }catch(Exception $e){
      $this->assert($e instanceof Artovenry\CustomPostType\MetaAttributesNotDefined,
      "Meta attributes must be defined, when accessed."
      );
    }

    $this->assert($one->scheduled_on === null,
      "If no value is persisted, its value is null."
    );

  }

  function test_setter(){
    $one= TypeOne::build($this->one);
    $one->set("show_at_home", "yes");
    $this->assert($one->show_at_home === "yes");

    $one->set("show_at_home", false);
    $this->assert($one->show_at_home === "0",
      "Setter casts boolean false  into string '0'."
    );
    $one->set("show_at_home", true);
    $this->assert($one->show_at_home === "1",
      "Setter casts boolean true  into string '1'."
    );
    $one->set("show_at_home", 125);
    $this->assert($one->show_at_home === "125",
      "Setter casts its value into string."
    );
    $one->set("show_at_home", 1.2e3);
    $this->assert($one->show_at_home === "1200",
      "Setter casts its value into string."
    );

    foreach([null, [1,2,3],(object)[4,5,6]] as $item){
      try{$one->show_at_home= $item;
      }catch(Exception $e){
        $this->assert($e instanceof Artovenry\CustomPostType\ValueIsNotScalar);
      }
    }
  }

  function test_delation(){
    global $wpdb;
    $one= TypeOne::build($this->one);
    $one->delete("show_at_home");
    $this->assert($one->show_at_home === null.
      "If an attribute is deleted, its value is null."
    );
  }
}
