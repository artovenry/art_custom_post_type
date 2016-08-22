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
