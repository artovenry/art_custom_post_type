<?
namespace Artovenry\CustomPostType;
trait PostMeta{

  function set($attr_name_or_hash_of_attrs=[], $value= null){
    if(is_string($attr_name_or_hash_of_attrs))
      return $this->set_attribute($attr_name_or_hash_of_attrs, $value);
    if(!is_array($attr_name_or_hash_of_attrs))return false;
    foreach($attr_name_or_hash_of_attrs as $attr=>$value)
      $this->attribute_exists($attr, true);
    foreach($attr_name_or_hash_of_attrs as $attr=>$value)
      $this->set_attribute($attr, $value);
    return true;
  }
  function get($name){
    $rs= get_post_meta($this->ID, static::meta_key_for($name));
    if(empty($rs))return null;
    return array_shift($rs);
  }
  function delete($name){
    return delete_post_meta($this->ID, static::meta_key_for($name));
  }

  //private
    private function set_attribute($name, $value){
      if(($value= stringify($value)) === false)return false;
      $rs= update_post_meta($this->ID, static::meta_key_for($name), $value);
      if(is_int($rs))return true;              //inserted
      if($rs === true)return true;             //updated
      if($this->$name === $value)return true; //noop(already persisted the same value)
      return false; //failed
    }
    private function attribute_exists($name, $raise= false){
      try{
        $rs= static::meta_key_for($name);
      }catch(AttributeNotFound $e){
        if($raise)throw $e;
        return false;
      }
      return true;
    }
}
