<?
class TestPostMetaQuery extends  Artovenry\CustomPostType\TestCase{
  function setup(){
    parent::setup();
    //{title: Title:1 show_at_home: 5 scheduled_on: 2016-12-01}
    //{title: Title:2 show_at_home: 4}
    //{title: Title:3 show_at_home: 3 scheduled_on: 2016-12-01}
    //{title: Title:4 show_at_home: 2}
    //{title: Title:5 show_at_home: 1 scheduled_on: 2016-12-01}
    foreach([1,2,3,4,5] as $i){
      $id= $this->insert("type_one", "Title:$i");
      TypeOne::build($id)->set("show_at_home", 6 - $i);
      if($i % 2 === 1)TypeOne::build($id)->set("scheduled_on", "2016-12-01");
    }
  }

  function test_noop(){}

  function test_meta_query(){
    $this->assert("Title:5" === TypeOne::fetch([
      "meta_key"   => "show_at_home",
      "meta_value" => "1"
    ])[0]->post_title, "You can specify the value of 'meta_key' as an attribute name defined at your model class.");

    $query=[
      "meta_query"=>["key"=>"scheduled_on", "value"=>"2016-12-01"],
      "meta_key"=>"show_at_home",
      "order"=>"ASC",
      "orderby"=>"meta_value",
    ];

    $this->assertCount(3, TypeOne::fetch($query), "You can specify the value of 'key' at 'meta_query' as an attribute name defined at your model class.");

    $this->assert("Title:1" === TypeOne::fetch($query)[2]->post_title, "You can specify the value of 'key' at 'meta_query' as an attribute name defined at your model class.");


  }

}
