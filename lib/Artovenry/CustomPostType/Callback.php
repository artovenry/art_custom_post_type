<?
namespace Artovenry\CustomPostType;

class Callback{
  static function after_save($post_id, $post, $updated){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if($post->post_status === "auto-draft")return;
    if(!isset($_POST[PREFIX . "meta_boxes"]))return;
    if(!self::is_authorized($post))
      if(ART_ENV === "development")throw new RequestNotAuthenticated;


    $class= toCamelCase($post->post_type);
    if(!($meta_attributes= $class::meta_attributes()))return;
    $params= $_POST[PREFIX . "meta_boxes"][$post->post_type];
    if(!is_array($params))return;

    try{
      $class::build($post)->set($params);
    }catch(Error $e){
      if(ART_ENV === "development")throw $e;
    }
  }
  //private
    private static function is_authorized($post){
      if(!current_user_can("edit_post", $post->ID)) return false;
      if(!is_user_logged_in()) return false;

      $class= toCamelCase($post->post_type);
      if(!($meta_boxes= $class::meta_boxes()))return true;

      foreach($meta_boxes as $item){
        $nonce_name= CsrfAuthorization::metabox_csrf_name_for($item["name"], $post->post_type);
        $nonce_key= CsrfAuthorization::metabox_csrf_key_for($item["name"], $post->post_type);
        $value= $_POST[$nonce_name];
        if(!CsrfAuthorization::verify($value, $nonce_key)) return false;
      }
      return true;
    }
}
