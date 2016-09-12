<?
namespace Artovenry\CustomPostType\Integration;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\NoSuchElementException;

class TestCase extends \Artovenry\CustomPostType\TestCase{
  use Assertion;
  const SELENIUM_HOST= "http://localhost:4444/wd/hub";
  const DEFAULT_APP_ROOT= "http://127.0.0.1:3000";
  private $captured= false;
  private $driver;

  function setup(){
    parent::setup();
    if(!empty($this->$driver))return;

    $wp_url= getenv("WP_URL");
    $this->root= empty($wp_url)? self::DEFAULT_APP_ROOT: $wp_url;
    $this->driver= RemoteWebDriver::create(self::SELENIUM_HOST, DesiredCapabilities::chrome());
  }

  function tearDown(){
    $this->driver->close();
    $this->driver->quit();
  }

  //protected
    protected function capture($message="", $automatic= false){
      if($automatic && $this->captured)return;
      $this->captured= true;

      if(!empty($message))$message .= _;
      date_default_timezone_set('Asia/Tokyo');
      $now= date('G-i-s-Ymd');
      $source= $this->driver->getPageSource();
      $path= join("/", [
        TEST_ROOT ,
        "screenshots",
        $now . $message . get_class($this)
      ]);
      $this->driver->takeScreenshot($path . ".png");
      file_put_contents($path . ".html", $source);
    }
    protected function write($selector, $value){
      $this->click($selector);
      $this->driver->getKeyboard()->sendKeys($value);
    }
    protected function click($selector){
      $this->take($selector)->click();
    }
    protected function take($selector){
      if(($rs= $this->find($selector)) !== [])
        return array_shift($rs);
      $this->capture("NoSuchElementException", true);
      throw new NoSuchElementException("");
    }
    protected function find($selector){
      return $this->driver->findElements(WebDriverBy::cssSelector($selector));
    }
    protected function navigateTo($relativePath){
      $absUrl= join("", [$this->root, $relativePath]);
      $this->driver->get($absUrl);
    }
}
