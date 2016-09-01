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
    $args= wp_parse_args($args, ["posts_per_page"=>$limit]);
    $posts= static::fetch($args);
    return count($posts) === 1 ? array_shift($posts) : $posts;
  }
  static function all($query=[]){
    return static::take(-1, $query);
  }
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
    return static::parse_meta_query($query);
    }

    private static function parse_meta_query($query){
      if(!empty($query["meta_key"]))
        $query["meta_key"]= static::meta_key_for($query["meta_key"]);
      if(empty($query["meta_query"]) OR !is_array($query["meta_query"]))return $query;
      array_walk_recursive($query["meta_query"], function(&$item, $key){
        if($key === "key")$item= static::meta_key_for($item);
      });
      return $query;
    }
}
