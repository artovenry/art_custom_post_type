<?
class Event extends Artovenry\CustomPostType\Base{
  static $post_type_options= [
    "label" => "Our Big Event!",
  ];


  //array or function
  static $meta_attributes= ["show_at_home", "scheduled_on"];

  //array or function
  static $meta_boxes=[
    [
      "name"=>    "option",
      "label"=>   "設定",
    ],
    [
      "name"=>    "hoge",
      "template"=>   "boge"
    ],
  ];
}
