<?
namespace Artovenry\CustomPostType;
use \MtHaml;

class Haml{
  const HAML= ".html.haml";

  static function render_metabox($template, $args=[]){
    $haml= new MtHaml\Environment("php");
    $executor= new MtHaml\Support\Php\Executor($haml,["cache"=> sys_get_temp_dir() . "/haml"]);
    $path= join("/", [get_template_directory(), META_BOXES, $template]);
    if(is_readable($path . self::HAML)):
      $executor->display($path . self::HAML, $args);
    elseif(is_readable($path . ".php")):
      include($path . ".php");
    else:
      throw new TemplateNotFound($path);
    endif;
  }
}
