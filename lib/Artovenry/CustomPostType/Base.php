<?
namespace Artovenry\CustomPostType;
abstract class Base{
  static $default_post_type_options=[
    "public"          => true,
    "hierarchical"    => false,
    "rewrite"         => false,
    "supports"        => ["title, editor", "author", "thumbnail", "excerpt", "revisions"],
  ];

  static function extract_options($name){
    if(defined(static::$$name)) return static::$$name;
    $method_name= join("::", [get_called_class(), $name]);
    if(is_callable($method_name)) return call_user_func($method_name);
    if(ART_ENV === "development")throw new Error("Option: '$name' is not defined.");
    return false;
  }
}
