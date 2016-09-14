<?
class TypeOne extends Artovenry\CustomPostType\Base{
  static $meta_attributes=["show_at_home", "scheduled_on","file_one_path", "file_two_path"];
  static $meta_boxes=[
    ["name"=>"uploader"]
  ];
}
