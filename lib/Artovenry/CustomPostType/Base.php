<?
namespace Artovenry\CustomPostType;
abstract class Base{
  const IDENTIFIER_LENGTH_LIMIT= 20;
  static $default_post_type_options=[
    "public"          => true,
    "hierarchical"    => false,
    "rewrite"         => false,
    "support"        => ["title, editor", "author", "thumbnail", "excerpt", "revisions"],
  ];

  static function extract_static_for($name){
    if(isset(static::$$name) && !empty(static::$$name))
      return static::$$name;
    $method_name= join("::", [get_called_class(), $name]);
    if(is_callable($method_name))
      return call_user_func($method_name);
    return false;
  }

  static function build($post_or_post_id){
    return new static($post_or_post_id);
  }
  static function post_type(){
    $str= toLowerCase(get_called_class());
    if(strlen($str) > self::IDENTIFIER_LENGTH_LIMIT){
      throw new Error("Post Type name length must be less than 20chars(including hyphens).");
    }
    return $str;
  }

  //private
    private function __construct($post_or_post_id){
      $p= $post_or_post_id;
      if(is_int($p))$p= get_post($p);
      if(!$p)throw new RecordNotFound($post_or_post_id);
      if(!($p instanceof \WP_Post)) throw new RecordNotWpPost();
      if($p->post_type !== self::post_type())
        throw new RecordTypeMismatch($p->post_type, self::post_type());

      $this->post= $p;
      $this->post_id= $p->ID;
    }
}
