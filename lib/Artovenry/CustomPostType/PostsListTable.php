<?
namespace Artovenry\CustomPostType;

class PostsListTable{
	private static $built_in_columns=[
		"date", "title", "comments", "author"
	];
	private $post_type;
	private $columns= [];
	private $order= [];



	static function initialize($class){
		$instance= new self($class);
		$post_type= $class::post_type();
		add_action("parse_request", function($wp) use($post_type){
			if(!is_admin())return;
			if("edit-{$post_type}" !== get_current_screen()->id)return;
			if(isset($_GET["meta_key"]))
				$wp->query_vars["meta_key"]= $_GET["meta_key"];
		});
		add_action("load-edit.php", function() use($instance, $post_type){
			add_filter("manage_edit-{$post_type}_columns",[$instance, "register_columns"]);
			add_action("manage_{$post_type}_posts_custom_column", [$instance, "render"], 10, 2);

			//Support responsive view
			add_filter("list_table_primary_column", [$instance, "list_table_primary_column"]);

			//Supports sorting fort custom columns.
			add_action("admin_head", [$instance, "js"]);
			add_filter("manage_edit-{$post_type}_sortable_columns",[$instance, "sortable_columns"]);
		});
	}

	private function __construct($class){
		$this->post_type= $class::post_type();
		$options= $class::posts_list_table();
		if(isset($options["columns"]))
			$this->columns=  $options["columns"];
		if(isset($options["order"]))
			$this->order= $options["order"];
	}

	function list_table_primary_column($default){
		if(get_current_screen()->post_type != $this->post_type)return $default;
		if(!$this->order)return $default;
		return array_shift($this->order);
	}

	function js(){
		$screen= get_current_screen();
		if($screen->post_type !== $this->post_type)return;
		wp_enqueue_script(PREFIX . "post-table", plugins_url(PLUGIN_NAME) . "/js/post-table.js", ["jquery"]);
		$postTableColumns= [];
		foreach($this->columns as $key=>$value){
			$attr= empty($value["meta_attribute"])? $key: $value["meta_attribute"];
			$class= toCamelCase($this->post_type);
			if(!$class::meta_key_for($attr, false))continue;
			$postTableColumns[$key]= $class::meta_key_for($attr);
		}
		wp_localize_script(PREFIX . "post-table", PREFIX . "PostTableColumns", $postTableColumns);
	}

	function register_columns($default_columns){
		$columns= $this->build_columns();
		if(empty($columns))return $default_columns;
		foreach($default_columns as $key=>$value)
			if(array_key_exists($key, $columns))
				$columns[$key]= $value;
		return $columns;
	}
	function render($column_name, $post_id){
		$option= $this->columns[$column_name];
		if(empty($option))return;
		$class= toCamelCase($this->post_type);
		$record= $class::find($post_id);
		if(isset($option["render"]) AND is_callable($option["render"]))
			return $option["render"]($record);
		$attr_name= empty($option["meta_attribute"]) ? $column_name : $option["meta_attribute"];
		if($class::meta_key_for($attr_name, false))
			echo $record->$attr_name;
	}

	function sortable_columns($columns){
		foreach($this->columns as $key=>$item)
			$columns[$key]= $key;
		return $columns;
	}

	//private
		private function build_columns(){
			if(empty($this->order))return false;
			$columns= array_filter($this->order, function($item){
				return in_array($item, array_merge($this->built_in_columns(), array_keys($this->columns)));
			});
			array_unshift($columns, "cb");
			$rs=[];
			foreach($columns as $column)
				if(array_key_exists($column, $this->columns))
					$rs[$column]= $this->columns[$column]["label"];
				else
					$rs[$column]= null;
			return $rs;
		}
		private function built_in_columns(){
			$columns= array_filter(self::$built_in_columns, function($item){
				if($item=="title")
					return post_type_supports($this->post_type, "title");
				if($item=="comments")
					return post_type_supports($this->post_type, "comments");
				if($item=="author")
					return post_type_supports($this->post_type, "author");
				return true;
			});
			$tax_columns= array_map(function($item){
				return "taxonomy-{$item}";
			}, get_object_taxonomies($this->post_type));
			return array_merge($columns, $tax_columns);
		}
}
