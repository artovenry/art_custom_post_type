<?
namespace Artovenry\CustomPostType;
use \MtHaml;

class Haml{
  const VIEWPATH= "views/meta_boxes";
  const HAML= ".html.haml";

  static function render_metabox($template, $args=[]){
    $haml= new MtHaml\Environment("php");
    $executor= new MtHaml\Support\Php\Executor($haml,["cache"=> sys_get_temp_dir() . "/haml"]);
    $path= join("/", [get_template_directory(), self::VIEWPATH, $template]);
    $nonce_key= PREFIX . str_replace("/", "_", $template);
    $nonce_name= CsrfAuthorization::token_for(str_replace("/", "_", $template));
    if(is_readable($path . self::HAML)):
      echo wp_nonce_field($nonce_key, $nonce_name, true, false);
      $executor->display($path . self::HAML, $args);
    elseif(is_readable($path . ".php")):
      echo wp_nonce_field($nonce_key, $nonce_name, true, false);
      include($path);
    elseif(ART_ENV === "development"):
      exit("Template Not Found!");
    endif;
  }
}
