<?
namespace Artovenry\CustomPostType;
abstract class Base{
  const IDENTIFIER_LENGTH_LIMIT= 20;
  use Query;
  use PostMeta;
  private static $macros=[
    "post_type_options",
    "meta_attributes",
    "meta_boxes"
  ];

  private $post;

  static $default_post_type_options=[
    "public"          => true,
    "hierarchical"    => false,
    "rewrite"         => false,
    "support"        => ["title, editor", "author", "thumbnail", "excerpt", "revisions"],
  ];


  function __get($name){
    if($name==="post")return $this->post;
    if($attrs= self::extract_static_for("meta_attributes")){
      foreach($attrs as $attr)
        if($name === $attr)return $this->get_meta($attr);
    }
    return $this->post->$name;
  }

  static function __callStatic($name, $args){
    foreach(self::$macros as $macro){
      if($macro !== $name)continue;
      if(isset(static::$$name))return static::$$name;
      if(is_callable("get_called_class()::{$name}"))return call_user_func_array($name, $args);
      return false;
    }
    throw new Error("Macro {$name} is not defined.");
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
    }
}
