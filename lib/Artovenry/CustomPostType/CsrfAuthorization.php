<?
namespace Artovenry\CustomPostType;

class CsrfAuthorization{
  static function verify($value, $key){
    return wp_verify_nonce($value, $key);
  }
  static function metabox_csrf_key_for($metabox_name,$post_type){
    return PREFIX . "{$post_type}_{$metabox_name}";
  }
  static function metabox_csrf_name_for($metabox_name,$post_type){
    return "_" . PREFIX . "nonce_" . "{$post_type}_{$metabox_name}";
  }
  static function metabox_csrf_hidden_tag_for($metabox_name,$post_type){
    return wp_nonce_field(self::metabox_csrf_key_for($metabox_name, $post_type), self::metabox_csrf_name_for($metabox_name, $post_type), false, false);
  }
}
