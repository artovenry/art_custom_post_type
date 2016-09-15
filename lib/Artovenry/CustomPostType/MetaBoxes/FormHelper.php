<?
namespace Artovenry\CustomPostType\MetaBoxes;

class FormHelper{

  //alias for field_name_for
  function name(){
    return call_user_func_array([$this, "field_name_for"], func_get_args());
  }
  function field_name_for($name){
    return \Artovenry\CustomPostType\PREFIX. "meta_boxes{$name}";
  }
  function text_field_for($record, $attr){
    $tag= '<input type="text" name="%s" value="%s" />';
    $name= \Artovenry\CustomPostType\PREFIX. "meta_boxes[{$record->post_type}][{$attr}]";
    return sprintf($tag, $name, esc_attr($record->$attr));
  }
  function file_field_for($record, $attr){
    $tag= '<input type="file" name="%s"  />';
    $name= \Artovenry\CustomPostType\PREFIX. "meta_boxes[{$record->post_type}][{$attr}]";
    return sprintf($tag, $name);
  }
}
