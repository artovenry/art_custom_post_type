<?
class Event extends Artovenry\CustomPostType\Base{
  static $post_type_options= [
    "label" => "Our Big Event!",
  ];


  //array or function
  static $meta_attributes= ["show_at_home","scheduled_on"];

  //array or function
  static function meta_boxes(){
    $boxes= [
      ["name"=>    "option","label"=>   "設定",],
      ["name"=>    "hoge","template"=>   "boge"],
      ["name"=> "shoot", "render"=> "shooter"],
      ["name"=> "woops",
        "args"=>["foo"=>"FOO", "baa"=>"BAA"],
        "render"=> function($locals){
          printf("Post Type: %s, foo: %s, baa: %s", $locals["post_type"], $locals["args"]["foo"], $locals["args"]["baa"]);
        }
      ],
    ];
    return $boxes;
  }

  static function shooter($locals){
    echo "SHOOT";
  }
}
