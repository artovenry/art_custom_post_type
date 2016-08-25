<?
namespace Artovenry\CustomPostType;

//#[PERFORMANCE]
class Initializer{
	private $post_types= [];

	static function run(){
		try{
			$init= new self;
			$init->register();
			$init->register_callbacks();
		}catch(Error $e){
			if(ART_ENV === "development")throw $e;
			return false;
		}
	}

	function register(){
		add_action("init", function(){
			foreach($this->post_types as $post_type=>$options)
				register_post_type($post_type, $options);
		});
	}

	function register_callbacks(){
		add_action("save_post", function(){

		});
	}

	//private
		private function __construct(){
			if(!is_dir(get_template_directory() . "/" . MODELS))
				return false;
			foreach(glob(join("/", [get_template_directory(),MODELS, "/*.php"])) as $file){
				$class_name= basename($file, ".php");
				require $file;
				static::check($class_name);

				$post_type= $class_name::post_type();
				if(!($options= $class_name::post_type_options()))
					$options= [];
				if(empty($options["label"]))$options["label"]= $post_type;
				$options= array_merge(Base::$default_post_type_options, $options);
				$meta_boxes= MetaBox::create($class_name);
				$options["register_meta_box_cb"]= function() use($meta_boxes){
					foreach($meta_boxes as $item)$item->register();
				};
				$this->post_types[$post_type]= $options;
			}
		}

		//private
			private static function check($class_name){
				if(!class_exists($class_name))
					throw new Error("class {$class_name} is not found.");
				if(!is_subclass_of($class_name, "Artovenry\CustomPostType\Base"))
					throw new Error("class {$class_name} is not inherited from Artovenry\CustomPostType\Base.");
				if($meta_attributes= $class_name::meta_attributes())
					foreach($meta_attributes as $item)
						if(!preg_match(META_ATTRIBUTE_NAME_REGEXP, $item))throw new Error("Meta attribute name  must be " . META_ATTRIBUTE_NAME_REGEXP);
			}
}
