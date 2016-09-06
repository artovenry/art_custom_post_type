<?
namespace Artovenry\CustomPostType;
class MetaBox{
  const CONTEXT= "side";
  const PRIORITY= "core";
  const WP_ACTION_HOOKNAME= "art_render_metabox";
  const HELPER_IDENTIFIER= "_";

  private $options;
  private $post_type;
  private $prefixed_name;
  private $name;

  static function create($class_name){
    if(!($meta_boxes= $class_name::meta_boxes()))
      return [];

    $post_type= $class_name::post_type();
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
      "args"=> $args,
    ];
    $locals= array_merge($locals, [self::HELPER_IDENTIFIER=> new HelperProxy]);
    echo CsrfAuthorization::metabox_csrf_hidden_tag_for($this->options["name"], $this->post_type);
    try{
      if(is_callable($render)){
        call_user_func_array($render, [$locals]);
      }elseif(method_exists($class_name, $render)){
        call_user_func_array("{$class_name}::{$render}", [$locals]);
      }elseif(DEFAULT_RENDERER === "Haml"){
        Haml::render_metabox($template, $locals);
      }else{
        do_action(self::WP_ACTION_HOOKNAME, $locals, $this->options);
      }
    }catch(Error $e){
      if(ART_ENV === "development")throw $e;
      return false;
    }
  }

  //private
    private function __construct($post_type, $options){
      $this->post_type= $post_type;
      if(empty($options["name"]))
        throw new Error("Failed to create metabox.");
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

class HelperNotFound extends Error{}
class HelperProxy{
  private $helpers;
  function __call($name, $args){
    foreach($this->helpers as $helper)
      if(is_callable([$helper, $name]))
        return call_user_func_array([$helper, $name], $args);
    throw new HelperNotFound;
  }
  function __construct(){
    $this->helpers[]= new FormHelper;
  }
}
