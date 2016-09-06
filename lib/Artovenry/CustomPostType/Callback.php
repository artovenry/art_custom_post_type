<?
namespace Artovenry\CustomPostType;

class Callback{
  //Return FALSE if cancelling following custom callback!
  static function after_save($post_id, $post, $updated){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return false;
    if($post->post_status === "auto-draft")return false;

    //Unless `art_metaboxes` hash posted, cancel processing.
    if(!isset($_POST[PREFIX . "meta_boxes"]))return false;
    // if(empty($_POST[$post->post_type]))return false;

    if(!self::is_authorized($post)){
      if(ART_ENV === "development")throw new RequestNotAuthenticated;
      return false;
    }
    try{
      return self::persist_meta_attributes($post);
    }catch(Error $e){
      if(ART_ENV === "development")throw $e;
      return false;
    }
  }
  //private
    private static function persist_meta_attributes($post){
      $class= toCamelCase($post->post_type);
      if(!($meta_attributes= $class::meta_attributes()))return;

      $params= $_POST[$post->post_type];
      if(!is_array($params))return;
      $class::build($post)->set($params);
      return;
    }
    private static function is_authorized($post){
      if(!current_user_can("edit_post", $post->ID)) return false;
      if(!is_user_logged_in()) return false;

      $class= toCamelCase($post->post_type);
      if(!($meta_boxes= $class::meta_boxes()))return true;

      foreach($meta_boxes as $item){
        $nonce_name= CsrfAuthorization::metabox_csrf_name_for($item["name"], "{$post->post_type}_");
        $nonce_key= CsrfAuthorization::metabox_csrf_key_for($item["name"], "{$post->post_type}_");
        $value= $_POST[$nonce_name];
        if(!CsrfAuthorization::verify($value, $nonce_key)) return false;
      }
      return true;
    }
}
