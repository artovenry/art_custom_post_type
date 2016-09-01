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
				if(empty($options["label"]))$options["label"]= $post_type;
				$options= array_merge(Base::$default_post_type_options, $options);
				$meta_boxes= MetaBox::create(get_called_class());
				$options["register_meta_box_cb"]= function() use($meta_boxes){
					foreach($meta_boxes as $item)$item->register();
				};
				register_post_type(static::post_type(), $options);
			});
		}
		private static function register_callbacks(){
			add_action("save_post", function(){});
		}
}
