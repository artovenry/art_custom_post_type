<?
namespace Artovenry\CustomPostType;

class UploadedFileError extends Error{}
class InvalidUploadError extends UploadedFileError{}
class UploadFailed extends UploadedFileError{}
class ExceedsMaxUploadSize extends UploadedFileError{
  function __construct($code){
    switch ($code):
      case UPLOAD_ERR_INI_SIZE:
        $max= ini_get("upload_max_filesize");
        parent::__construct("Max size of uploading is {$max}.");
        break;
      case UPLOAD_ERR_FORM_SIZE:
        parent::__construct("Uploading file is too big.");
        break;
    endswitch;
  }
}

class UploadedFile{
  public $hash;
  function __construct($hash){
    $this->hash= $hash;
    $this->check();
  }
  function get($name){
    return $this->hash[$name];
  }
  function check(){
    switch ($this->hash["error"]):
      case UPLOAD_ERR_OK:
        return true;
        break;
      case UPLOAD_ERR_INI_SIZE:
        throw new ExceedsMaxUploadSize(UPLOAD_ERR_INI_SIZE);
        break;
      case UPLOAD_ERR_FORM_SIZE:
        throw new ExceedsMaxUploadSize(UPLOAD_ERR_FORM_SIZE);
        break;
      case UPLOAD_ERR_PARTIAL:
        throw new UploadFailed;
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new UploadFailed;
        break;
      case UPLOAD_ERR_NO_TMP_DIR:
        throw new UploadFailed;
        break;
      case UPLOAD_ERR_CANT_WRITE:
        throw new UploadFailed;
        break;
      case UPLOAD_ERR_EXTENSION:
        throw new UploadFailed;
        break;
      default:
        throw new UploadFailed;
        break;
    endswitch;
    if(!is_uploaded_file($this->hash["tmp_name"]))
      throw new InvalidUploadError;

  }
}
