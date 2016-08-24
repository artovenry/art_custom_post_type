<?
namespace Artovenry\CustomPostType;
trait Query{
  static function find($id, $raise= false){
    if($post= get_post($id))
      return static::build($post);
    if($raise) throw new RecordNotFound;
    return false;
  }
  static function take(){}
  static function all(){}
  static function fetch(){}

}
