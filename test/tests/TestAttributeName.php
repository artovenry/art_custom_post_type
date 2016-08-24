<?
class TestAttributeName extends Artovenry\CustomPostType\TestCase{
  function test_attribute_name(){
    $regexp= Artovenry\CustomPostType\META_ATTRIBUTE_NAME_REGEXP;
    $this->assert(!!preg_match($regexp, "hogehoge"));
    $this->assert(!!preg_match($regexp, "hoge1"));
    $this->assert(!!preg_match($regexp, "h"));
    $this->assert(!!preg_match($regexp, "h_"));
    $this->assert(!!preg_match($regexp, "h_1"));

    $this->assert(!!!preg_match($regexp, "_h"));
    $this->assert(!!!preg_match($regexp, "_1_hoge"));
    $this->assert(!!!preg_match($regexp, "_hoge"));
    $this->assert(!!!preg_match($regexp, "_hoge_1"));
    $this->assert(!!!preg_match($regexp, "_"));
    $this->assert(!!!preg_match($regexp, "___"));
    $this->assert(!!!preg_match($regexp, "_1"));
    $this->assert(!!!preg_match($regexp, "1"));
    $this->assert(!!!preg_match($regexp, "1a"));
    $this->assert(!!!preg_match($regexp, ""));
  }
}
