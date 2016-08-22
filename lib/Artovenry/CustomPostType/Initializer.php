<?
namespace Artovenry\CustomPostType;

class Initializer{
	private $post_types= [];

	static function run(){
		$init= new self;
		$init->register();
		$init->register_callbacks();
	}

	function register(){
		add_action("init", function(){
			foreach($this->post_types as $options)
				register_post_type($options["name"], $options);
		});
	}

	function register_callbacks(){
		add_action("save_post", function(){

		});
	}

	//private
		private function __construct(){
			$this->extract_models();
		}
		private function extract_models(){
			if(!is_dir(get_template_directory() . "/models"))
				return false;
			foreach(glob(get_template_directory() . "/models/*.php") as $file){
				$class_name= basename($file, ".php");
				require $file;
				if(!class_exists($class_name)){
					if(ART_ENV === "development")throw new Error("class {$class_name} is not found.");
					return false;
				}
				if(!is_subclass_of($class_name, "Artovenry\CustomPostType\Base")){
					if(ART_ENV === "development")throw new Error("class {$class_name} is not inherited from Artovenry\CustomPostType\Base.");
					return false;
				}
				$this->post_types[$class_name]= array_merge(
					Base::$default_post_type_options, $class_name::extract_options("post_type_options")
				);
			}
		}
}
