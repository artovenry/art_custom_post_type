<?
namespace Artovenry\CustomPostType;
class Route{
  private $post_type;
  private $routes;

  function __construct($post_type, $routes=[]){
    $this->post_type= $post_type;
    $this->routes= array_merge($routes, $this->default_routes());
  }

  function draw(){
    foreach($this->routes as $item)
      add_rewrite_rule($item[0], $item[1], "top");
    add_filter("post_type_link", function($url, $post){
      return $this->permalink($url, $post);
    },10, 2);
  }
  //private
    private function default_routes(){
      $post_type= $this->post_type;
      return [
        ["{$post_type}/?$", 'index.php?post_type=' . $post_type],
        ["{$post_type}/(\d+)/?$", 'index.php?p=$matches[1]&post_type=' . $post_type],
        ["{$post_type}/archive/(\d{4})/(\d{1,2})/?$", 'index.php?year=$matches[1]&monthnum=$matches[2]&post_type=' . $post_type],
        ["{$post_type}/archive/(\d{4})/?$", 'index.php?year=$matches[1]&post_type=' . $post_type],
      ];
    }
    private function permalink($url, $post){
      $post_type= $this->post_type;
      if($post->post_type !== $post_type)return $url;
      $status= get_post_status($post->ID);
      if(in_array($status, ['draft', 'pending', 'auto-draft', 'future']))
        return home_url(add_query_arg([
          "post_type"=>$post_type,
          "p"=>$post->ID
        ], ""));
      return home_url("{$post_type}/{$post->ID}");
    }
}
