<?
class Event extends Artovenry\CustomPostType\Base{
  static $post_type_options= [
    "label" => "Our Big Event!",
    "has_archive"=> true,
  ];

  static function routes(){
    add_filter("query_vars", function($vars){
      return array_merge($vars,["posts_per_page", "meta_key", "meta_value"]);
    });
    return [
      ["featured_events/?$", "index.php?post_type=event&posts_per_page=3&order=desc"],
      ["best_event/?$", "index.php?post_type=event&posts_per_page=1&order=desc&meta_key=art_event_best&meta_value=finest"],
    ];
  }


  static function posts_list_table(){
    return [
      "order"=>["show_at_home", "date", "title", "author"],
      "columns"=>[
        "show_at_home"=>[
          "label"=>"SHOW AT HOME",
          //"render"=>function($record){echo $record->post_title;}
        ]
      ]
    ];
  }

  //array or function
  static $meta_attributes= ["show_at_home","scheduled_on", "best"];

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

  static function after_save($post_id){
    // if($_POST["event"]["show_at_home"]["delete"] === "1")
    //   self::build($post_id)->delete("show_at_home");
    //
  }
  static function before_save($sanitized, $raw){
    //var_dump($_POST["event"]);exit;
    return $sanitized;
  }
}
