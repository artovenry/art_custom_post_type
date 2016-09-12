<?
class TestRoute extends Artovenry\CustomPostType\Integration\TestCase{
  function testPermalink(){
    $id= wp_insert_post([
      "post_title"=>"green garden",
      "post_type"=> "event",
      "post_status"=>"publish",
      "post_author"=>1
    ]);
    $this->navigateTo("/event/{$id}");
    $this->assertElementHasAttribute("article",["title"=>"green garden"]);
  }
}
