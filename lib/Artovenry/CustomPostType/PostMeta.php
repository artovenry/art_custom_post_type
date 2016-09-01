<?
namespace Artovenry\CustomPostType;
trait PostMeta{
  function set($attr_name_or_hash_of_attrs=[], $value= null){
    if(is_string($attr_name_or_hash_of_attrs))
    return $this->set_attribute($attr_name_or_hash_of_attrs, $value);
    foreach($attr_name_or_hash_of_attrs as $attr=>$value){
      static::attribute_exists($attr, true);
      static::is_stringish($attr, true);
    }
    foreach($attr_name_or_hash_of_attrs as $attr=>$value)
      $this->set_attribute($attr, $value);
    return true;
  }
  function get($name){
    $rs= get_post_meta($this->ID, static::meta_key_for($name), true);
    if($rs === "")return null;
    return $rs;
  }
  function delete($name){
    return delete_post_meta($this->ID, static::meta_key_for($name));
  }

  //protected
    protected function set_attribute($name, $value, $raise= true){
      $value= self::stringish($value);
      $rs= update_post_meta($this->ID, static::meta_key_for($name), $value);
      if($rs === true)return true;
      if($value === $this->$name)return true;
      if($raise)throw new AttributeNotSaved;
      return false;
    }
    protected function is_stringish($value, $raise= false){
      if(!is_bool($value) AND !is_string($value) AND !is_numeric($value)){
        if($raise)throw new ValueIsNotScalar;
        return false;
      }
      return true;
    }
    protected static function stringish($value){
      static::is_stringish($value, true);
      if($value === true)return "1";
      if($value === false)return "0";
      return (string) $value;
    }
    protected static function attribute_exists($name, $raise= false){
      try{
        $rs= static::meta_key_for($name);
      }catch(AttributeNotFound $e){
        if($raise)throw $e;
        return false;
      }
      return true;
    }
    protected static function meta_key_for($name){
      if(!($attrs= static::meta_attributes()))throw new MetaAttributesNotDefined;
      if(array_search($name, $attrs) === false)throw new AttributeNotFound;
			if(!preg_match(self::META_ATTRIBUTE_NAME_REGEXP, $name))throw new Error("Meta attribute name  must be " . self::META_ATTRIBUTE_NAME_REGEXP);
      return PREFIX . join("_", [static::post_type(), $name]);
    }
}
