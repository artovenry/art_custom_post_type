<?
namespace Artovenry\CustomPostType;

class CsrfAuthorization{
  static function verify($value, $key){
    return wp_verify_nonce($value, $key);
  }
  static function token_for($name){
    return _ . PREFIX . "nonce_" . $name;
  }
}
