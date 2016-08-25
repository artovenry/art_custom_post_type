<?
namespace Artovenry\CustomPostType;
trait PostMeta{
  function set($name, $value){
    return update_post_meta($this->ID, static::meta_key_for($name), self::stringish($value));
  }
  function get($name){
    $rs= get_post_meta($this->ID, static::meta_key_for($name), true);
    if(rs === false)return null;
    return $rs;
  }
  function delete($name){
    return delete_post_meta($this->ID, static::meta_key_for($name));
  }

  //protected
    protected static function stringish($value){
      if(!is_bool($value) AND !is_string($value) AND !is_numeric($value))
        throw new TypeIsNotScalar;
      if($value === true)return "1";
      if($value === false)return "0";
      return (string) $value;
    }
    protected static function meta_key_for($name){
      if(!($attrs= static::meta_attributes()))throw new MetaAttributesNotDefined;
      if(array_search($name, $attrs) === false)throw new AttributeNotFound;
      return PREFIX . join("_", [static::post_type(), $name]);
    }
}
