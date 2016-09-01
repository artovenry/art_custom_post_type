<?
namespace Artovenry\CustomPostType;

class Callback{
  //Return FALSE if cancelling following custom callback!
  static function after_save($post_id, $post, $updated){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return false;
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
      if($post->is_auto_draft())return;

      $params= $_POST[$post->post_type];
      if(!is_array($params))return;
      $post->set($params);
      return;
    }
    private static function is_authorized($post){
      if(!current_user_can("edit_post", $post->ID)) return false;
      if(!is_user_logged_in()) return false;

      $class= toCamelCase($post->post_type);
      if(!($meta_boxes= $class::meta_boxes()))return true;

      foreach($meta_boxes as $item){
        $key= $item->nonce_key();
        $value= $_POST[$item->nonce_name()];
        if(!CsrfAuthorization::verify($value, $key)) return false;
      }
      return true;
    }
}
