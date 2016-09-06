<?
namespace Artovenry\CustomPostType;

function toLowerCase($arg=""){
  $str= ltrim(preg_replace("/[A-Z]/", '_${0}', $arg), "_");
  return strtolower($str);
}

function toCamelCase($arg=""){
  $str= preg_replace("/_[a-z]/", strtoupper('{$0}'), $arg);
  return ucfirst($str);
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
