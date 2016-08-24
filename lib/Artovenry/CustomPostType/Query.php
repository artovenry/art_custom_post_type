<?
namespace Artovenry\CustomPostType;
trait Query{
  static function find($id, $raise= false){
    if($post= get_post($id))
      return static::build($post);
    if($raise) throw new RecordNotFound;
    return false;
  }
  static function take($limit_or_query=1, $query=[]){
    if(!is_int($limit_or_query)){
      $limit= 1;
      $args= $limit_or_query;
    }else{
      $limit= $limit_or_query;
      $args= $query;
    }
    $args= array_merge(["posts_per_page"=>$limit], $args);
    $posts= static::fetch($args);
    return count($posts) === 1 ? array_shift($posts) : $posts;
  }
  static function all(){}
  static function fetch($args= []){
    $rs= [];
    foreach(get_posts(static::parse_query($args)) as $item)
      $rs[]= static::build($item);
    return $rs;
  }

  //private
    private static function parse_query($query){
    $defaults=[
      "post_type"=>static::post_type()
    ];
    $query= wp_parse_args($query, $defaults);
    return $query;
    //return static::parse_meta_query($query);
    }

}
