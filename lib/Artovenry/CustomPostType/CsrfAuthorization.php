<?
namespace Artovenry\CustomPostType;

class CsrfAuthorization{
  static function verify($value, $key){
    return wp_verify_nonce($value, $key);
  }
  static function metabox_csrf_key_for($metabox_name, $prefix=""){
    return PREFIX . "{$prefix}{$metabox_name}";
  }
  static function metabox_csrf_name_for($metabox_name, $prefix=""){
    return "_" . PREFIX . "nonce_" . "{$prefix}{$metabox_name}";
  }
  static function metabox_csrf_hidden_tag_for($metabox_name, $prefix=""){
    return wp_nonce_field(self::metabox_csrf_key_for($metabox_name, $prefix), self::metabox_csrf_name_for($metabox_name, $prefix), false, false);
  }
}
