<?
namespace Artovenry\CustomPostType;
abstract class Base{
  static $default_post_type_options=[
    "public"          => true,
    "hierarchical"    => false,
    "rewrite"         => false,
    "support"        => ["title, editor", "author", "thumbnail", "excerpt", "revisions"],
  ];

  static function extract_static_for($name){
    if(!empty(static::$$name)) return static::$$name;
    $method_name= join("::", [get_called_class(), $name]);
    if(is_callable($method_name)) return call_user_func($method_name);
    if(ART_ENV === "development")throw new Error("Static attribute or method: '$name' is not defined.");
    return false;
  }

  static function build($post_or_post_id){
    return new static($post_or_post_id);
  }
  static function post_type(){
    return toLowerCase(get_called_class());
  }

  //private
    private function __construct($post_or_post_id){
      $p= $post_or_post_id;
      $this->post= is_int($p)? get_post($p): $p;
      $this->post_id= is_int($p)? $p: $p->ID;
    }
}
