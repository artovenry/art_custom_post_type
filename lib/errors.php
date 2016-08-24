<?
namespace Artovenry\CustomPostType;
class Error extends \Exception{
  function __construct($message=""){
    parent::__construct($message);
  }
}
class RecordNotFound extends Error{}
class RecordNotWpPost extends Error{}
class RecordTypeMismatch extends Error{}
class TemplateNotFound extends Error{
  function __construct($path){
    parent::__construct("Template: '{$path}(.html.haml or .php)' Not Found!");
  }
}