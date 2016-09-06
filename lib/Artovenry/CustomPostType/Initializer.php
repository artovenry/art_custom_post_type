<?
namespace Artovenry\CustomPostType;

trait Initializer{
	static function initialize(){
		try{
			static::register_post_type();
			static::register_callbacks();
		}catch(Error $e){
			if(ART_ENV === "development")throw $e;
			return false;
		}
	}

	//private
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
			add_action("save_post_" . static::post_type(), function(){
				if(FALSE === call_user_func_array(__NAMESPACE__ . "\Callback::after_save", func_get_args()))
					return;
				if(method_exists(get_called_class(), "after_save"))
					call_user_func_array(get_called_class() . "::after_save", func_get_args());
			}, 10, 3);
			add_filter("wp_insert_post_data", function($sanitized, $raw){
				if(method_exists(get_called_class(), "before_save"))
					return call_user_func_array(get_called_class() . "::before_save", func_get_args());
				return $sanitized;
			}, 10, 2);
		}
}
