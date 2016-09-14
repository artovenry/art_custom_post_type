<?
namespace Artovenry\CustomPostType;

function toLowerCase($arg=""){
  $str= ltrim(preg_replace("/[A-Z]/", '_${0}', $arg), "_");
  return strtolower($str);
}

//type_one_two ->  typeOneTwo -> TypeOneTwo
function toCamelCase($arg=""){
  $words= explode("_", $arg);
  foreach($words as &$word)
    $word= ucfirst($word);
  return join('', $words);
}

function is_stringish($value, $raise= false){
  if(!is_bool($value) AND !is_string($value) AND !is_numeric($value)){
    if($raise)throw new ValueIsNotScalar;
    return false;
  }
  return true;
}
function stringify($value, $raise=false){
  if(!is_stringish($value, $raise))return false;
  if($value === true)return "1";
  if($value === false)return "0";
  return (string) $value;
}
