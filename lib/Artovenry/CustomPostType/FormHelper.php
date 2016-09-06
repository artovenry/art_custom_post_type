<?
namespace Artovenry\CustomPostType;

class FormHelper{
  function text_field_for($record, $attr){
    $tag= '<input type="text" name="%s" value="%s" />';
    $name= PREFIX. "meta_boxes[{$record->post_type}][{$attr}]";
    return sprintf($tag, $name, esc_attr($record->$attr));
  }
}
