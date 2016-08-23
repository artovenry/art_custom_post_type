<?
namespace Artovenry\CustomPostType;
class MetaBox{
  const CONTEXT= "side";
  const PRIORITY= "core";

  private $options;
  private $post_type;
  private $prefixed_name;
  private $name;

  static function create($class_name){
    $meta_boxes= $class_name::extract_static_for("meta_boxes");
    $post_type= $class_name::post_type();
    if(empty($meta_boxes))return [];
    return array_map(function($item) use($post_type){
      return new self($post_type, $item);
    }, $meta_boxes);
  }

  function register(){
    extract($this->options);
    add_meta_box($this->prefixed_name, $label,[$this, "render"], get_current_screen(), $context, $priority, $args);
  }
  function render($post, $args){
    extract($this->options);
    $class_name= toCamelCase($this->post_type);
    $post_type= $this->post_type;
    $locals= [
      "post_type"=> $post_type,
      $post_type=> $class_name::build($post),
      "args"=> $args
    ];
    if(is_callable($render)){
      call_user_func_array($render, [$locals]);
    }elseif(is_callable("{$class_name}::{$render}")){
      call_user_func_array("{$class_name}::{$render}", [$locals]);
    }elseif(DEFAULT_RENDERER === "Haml"){
      Haml::render_metabox($template, $locals);
    }else{
      do_action("art_render_metabox", $locals, $this->options);
    }
  }

  //private
    private function __construct($post_type, $options){
      $this->post_type= $post_type;
      if(empty($options["name"])){
        if(ART_ENV === "development")throw new Error("Failed to create metabox.");
        return false;
      }
      $this->name= join("_", [$post_type, $options["name"]]);
      $this->prefixed_name= PREFIX . $this->name;

      if(!isset($options["template"]))
        $options["template"]=  join("/", [$post_type, $options["name"]]);
      $options= array_merge([
        "context"=> self::CONTEXT,
        "priority"=> self::PRIORITY,
        "args"=> [],
      ], $options);

      if(!isset($options["label"]))
        $options["label"]= $options["name"];
      if(isset($options["args"]))
        $options["args"]= (array)$options["args"];
    	$this->options= $options;
    }
}
