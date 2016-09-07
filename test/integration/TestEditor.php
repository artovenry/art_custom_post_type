<?
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

class TestEditor extends Artovenry\CustomPostType\TestCase{
  const SELENIUM_HOST= "http://localhost:4444/wd/hub";
  const DEFAULT_APP_ROOT= "http://127.0.0.1:3000";
  private $captured= false;

  function setup(){
    parent::setup();
    $wp_url= getenv("WP_URL");
    $this->root= empty($wp_url)? self::DEFAULT_APP_ROOT: $wp_url;
    $this->driver= RemoteWebDriver::create(self::SELENIUM_HOST, DesiredCapabilities::phantomjs());
    $this->login();
  }
  function tearDown(){
    $this->driver->close();
  }
  function testBasic(){
    $this->navigateTo("/wp-admin/post-new.php?post_type=type_one");
    $this->write("#title", "Semishigure!");
    $this->click("#publish");
    $this->assertElementHasValue("#title", "Semishigure!",
      "When reached type-one editor page(no metaboxes),should save new post normally."
    );
  }

  //protected
    protected function capture($message="", $automatic= false){
      if($automatic && $this->captured)return;
      $this->captured= true;

      if(!empty($message))$message .= _;
      date_default_timezone_set('Asia/Tokyo');
      $now= date('YmdGis');
      $source= $this->driver->getPageSource();
      $path= join("/", [
        __DIR__ ,
        "screenshots",
        $message . get_class($this) . $now
      ]);
      $this->driver->takeScreenshot($path . ".png");
      file_put_contents($path . ".html", $source);
    }
    protected function assertElementHasValue($selector, $expected, $message=""){
      try{
        $value= $this->el($selector)->getAttribute("value");
        if($value !== $expected)$this->capture("ElementValueNotMatch", true);
        $this->assert($value === $expected, $message);
      }catch(Facebook\WebDriver\Exception\WebDriverException $e){
        $this->capture("ElementDoesntHaveValue", true);
        throw $e;
      }
    }
    protected function write($selector, $value){
      $this->click($selector);
      $this->driver->getKeyboard()->sendKeys($value);
    }
    protected function click($selector){
      $this->el($selector)->click();
    }
    protected function login(){
      $this->navigateTo("/wp-login.php");
      $this->write("#user_login", "admin");
      $this->write("#user_pass", "pass");
      $this->click("#wp-submit");
    }
    protected function el($selector){
      try{
        return $this->driver->findElement(WebDriverBy::cssSelector($selector));
      }catch(Facebook\WebDriver\Exception\NoSuchElementException $e){
        $this->capture("NoSuchElementException", true);
        throw $e;
      }
    }
    protected function navigateTo($relativePath){
      $absUrl= join("", [$this->root, $relativePath]);
      $this->driver->get($absUrl);
    }
}
