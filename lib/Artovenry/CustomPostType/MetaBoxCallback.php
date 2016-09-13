<?
namespace Artovenry\CustomPostType;

class MetaBoxCallback{
  private $post_type;
  private $class;

  static function initialize($class){
    new self($this);
  }

  function before_save_meta_boxes($post_id, $post, $updated){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!isset($_POST[PREFIX . "meta_boxes"]))return;
    if(!$this->is_authorized($post)){
      if(ART_ENV === "development")throw new RequestNotAuthenticated;
      return;
    }
    try{
      $params= $this->parse_request();
    }catch(UploadedFileError $e){
      if(ART_ENV==="development")throw $e;
      return;
    }
		if(!method_exists($this->class, "save_meta_boxes"))return;
    if(!$params)return;
    $record= $class::build($post);
		call_user_func_array($this->class . "::save_meta_boxes", [$record, $params, $updated]);
  }
  //private
    private function __construct($class){
      $this->class= $class;
      $this->post_type= toLowerCase($class);
      add_action("save_post_{$this->post_type}", [$this, "before_save_meta_boxes"], 10, 3);
    }
    private function parse_request(){
      $scope= PREFIX . "meta_boxes";
      $params= $_POST[$scope][$this->post_type];
      if(empty($post))return false;
      if(!empty($_FILES[$scope]))
        $params= array_merge($params, $this->parse_file_params());
    }
    private function parse_file_params(){
      $scope= PREFIX . "meta_boxes";
      $rs=[];
      foreach(array_keys($_FILES[$scope]) as $file_prop){
        foreach($_FILES[$scope][$file_prop][$this->post_type] as $name=>$item){
          if(empty($rs[$name]))$rs[$name]=[];
          $rs[$name][$file_prop]= $item;
        }
      }
      foreach($rs as $name=>&$item){
        if(empty($item["name"]))continue;
        $item= new UploadedFile($item);
      }
      return $rs;
    }

    private function is_authorized($post){
      if(!current_user_can("edit_post", $post->ID)) return false;
      if(!is_user_logged_in()) return false;
      $class= $this->class;
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
