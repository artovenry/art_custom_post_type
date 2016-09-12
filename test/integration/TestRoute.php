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

  function testArchivePage(){
    $hash= [];
    foreach(range(1,100) as $index){
      $id= wp_insert_post([
        "post_title"=>"green garden: {$index}",
        "post_type"=> "event",
        "post_status"=>"publish",
        "post_author"=>1
      ]);
      $ids[$index]= $id;
    }
    foreach(range(1,100) as $index){
      wp_insert_post([
        "post_title"=>"dark seaside: {$index}",
        "post_type"=> "type_one",
        "post_status"=>"publish",
        "post_author"=>1
      ]);
    }
    $this->navigateTo("/event");
    $this->assertElementExists("body.post-type-archive-event");
    $this->assertCount(10, $this->find("article"));
    $this->navigateTo("/event");
    $this->assertElementExists("body.post-type-archive-event");
    $this->assertCount(10, $this->find("article"));
  }


  function testDateBasedArchive(){
    $this->create2015Posts();

    $this->navigateTo("/event/archive/2015");
    $this->assertCount(6, $this->find("article"));
    $this->assertElementHasAttribute("body article:first-of-type",["title"=>"My birthday 2."]);
    $this->assertElementHasAttribute("body article:last-of-type",["title"=>"Eating osechi."]);


    $this->navigateTo("/event/archive/2015/01");
    $this->assertElementHasAttribute("body article:nth-of-type(1)",["title"=>"Drinking otoso."]);
    $this->assertElementHasAttribute("body article:nth-of-type(2)",["title"=>"Eating osechi."]);

    $this->navigateTo("/event/archive/2015/12");
    $this->assertElementHasAttribute("body article:nth-of-type(1)",["title"=>"My birthday 2."]);
    $this->assertElementHasAttribute("body article:nth-of-type(2)",["title"=>"My birthday 1."]);
    $this->assertElementHasAttribute("body article:nth-of-type(3)",["title"=>"My birthday 3."]);
  }

  function testCustomRoute(){
    $hash= $this->create2015Posts();
    $this->navigateTo("/featured_events");
    $this->assertElementHasAttribute("body article:last-of-type",["title"=>"My birthday 3."]);
    $event= Event::take();
    $event->set("best", "finest");
    $this->navigateTo("/best_event");
    $this->assertElementHasAttribute("body article:last-of-type",["title"=>$event->post_title]);

  }


  //private
    private function create2015Posts(){
      $hash=[
        ["2015-01-01 09:00:00", "Eating osechi."],
        ["2015-01-01 09:00:01", "Drinking otoso."],
        ["2015-12-09 11:00:00", "My birthday 1."],
        ["2015-12-10 11:00:00", "My birthday 2."],
        ["2015-12-01 11:00:00", "My birthday 3."],
        ["2015-11-11 11:11:11", "All one."]
      ];
      foreach($hash as &$item)
        array_unshift($item, wp_insert_post([
          "post_type"=>"event",
          "post_title"=>$item[1],
          "post_date"=>$item[0],
          "post_status"=>publish,
          "post_author"=>1
        ]));
      return $hash;
    }
}
