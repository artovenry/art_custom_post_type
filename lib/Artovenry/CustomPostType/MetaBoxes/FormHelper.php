<?
namespace Artovenry\CustomPostType\MetaBoxes;

class FormHelper{
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
