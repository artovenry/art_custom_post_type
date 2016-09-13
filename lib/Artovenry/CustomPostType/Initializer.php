<?
namespace Artovenry\CustomPostType;

trait Initializer{
	static function initialize(){
		try{
			static::register_post_type();
			static::register_callbacks();
			static::register_posts_list_table();
			static::register_routes();
		}catch(Error $e){
			if(ART_ENV === "development")throw $e;
			return false;
		}
	}

	//private
		private static function register_routes(){
			$routes= static::routes()? static::routes(): [];
			$route= new Route(static::post_type(), $routes);
			add_action("init", [$route, "draw"]);
		}
		private static function register_posts_list_table(){
			PostsListTable::initialize(get_called_class());
		}
		private static function register_post_type(){
			add_action("init", function(){
				if(!($options= static::post_type_options()))$options= [];
				if(empty($options["label"]))$options["label"]= static::post_type();
				$options= array_merge(Base::$default_post_type_options, $options);
				$meta_boxes= MetaBox::create(get_called_class());
				$options["register_meta_box_cb"]= function() use($meta_boxes){
					foreach($meta_boxes as $item)$item->register();
				};
				register_post_type(static::post_type(), $options);
			});
		}
		private static function register_callbacks(){
			//allows persisting posts which doesn't have any standard post attributes
			add_filter("wp_insert_post_empty_content", "__return_false");

			MetaBoxCallback::initialize(get_called_class());

			// add_action("save_post_" . static::post_type(), function(){
			// 	call_user_func_array(__NAMESPACE__ . "\Callback::before_save_meta_boxes", func_get_args());
				// if(method_exists(get_called_class(), "save_meta_boxes"))
				// 	call_user_func_array(get_called_class() . "::save_meta_boxes", func_get_args());
			// }, 10, 3);
			add_filter("wp_insert_post_data", function($sanitized, $raw){
				if(method_exists(get_called_class(), "before_save"))
					return call_user_func_array(get_called_class() . "::before_save", func_get_args());
				return $sanitized;
			}, 10, 2);
		}
}
