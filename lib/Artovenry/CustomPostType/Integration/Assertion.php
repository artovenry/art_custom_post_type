<?
namespace Artovenry\CustomPostType\Integration;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Exception\NoSuchElementException;
trait Assertion{

  //Asserts the `value` attibute of the given selector's element(if multiple, take first) is expected.
  protected function assertElementHasValue($selector, $expected, $message=""){
    try{
      $value= $this->take($selector)->getAttribute("value");
      if($value !== $expected)$this->capture("ElementValueNotMatch", true);
      $this->assert($value === $expected, $message);
    }catch(NoSuchElementException $e){
      $this->capture("ElementDoesntHaveValue", true);
      throw $e;
    }
  }
  protected function assertElementExists($selector, $message=""){
    try{
      $this->take($selector);
    }catch(NoSuchElementException $e){
      $this->assert(false, $message);
    }
    $this->assert(true);
  }
}
