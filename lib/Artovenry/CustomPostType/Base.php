<?
namespace Artovenry\CustomPostType;
abstract class Base{
  const IDENTIFIER_LENGTH_LIMIT= 20;
  const META_ATTRIBUTE_NAME_REGEXP= "/\A[a-z][a-z0-9_]*\z/";
  use Initializer, Query, PostMeta;
  private static $macros=[
    "post_type_options",
    "meta_attributes",
    "meta_boxes",
    "posts_list_table",
    "routes",
  ];
  private $post;

  static $default_post_type_options=[
    "public"          => true,
    "hierarchical"    => false,
    "rewrite"         => false,
    "has_archive"     => true,
    "supports"        => ["title", "editor", "author", "thumbnail", "excerpt", "revisions"],
  ];

  function __get($name){
    if($name==="post")return $this->post;
    if(isset($this->post->$name))return $this->post->$name;
    return $this->get($name);
  }

  static function __callStatic($name, $args){
    foreach(self::$macros as $macro){
      if($macro !== $name)continue;
      if(isset(static::$$name))return static::$$name;
      if(is_callable("get_called_class()::{$name}"))return call_user_func_array($name, $args);
      return null;
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

  static function meta_key_for($name, $raise=true){
    try{
      if(!($attrs= static::meta_attributes()))throw new MetaAttributesNotDefined;
      if(array_search($name, $attrs) === false)throw new AttributeNotFound;
      if(!preg_match(self::META_ATTRIBUTE_NAME_REGEXP, $name))throw new Error("Meta attribute name  must be " . self::META_ATTRIBUTE_NAME_REGEXP);
      return PREFIX . join("_", [static::post_type(), $name]);
    }catch(Error $e){
      if($raise)throw $e;
      return false;
    }
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
