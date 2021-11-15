<?php

if( !class_exists('Custom_Theme_Debugger') ):


class Custom_Theme_Debugger
{

  public $logDirectory;
  public $logFilePathGeneral;

  public $logFilePathCustom;

  public function __construct(){
    $this->logDirectory = dirname(__DIR__);
    // $this->logDirectory = get_template_directory_uri().'/troubleshoot';

    // devsunset\troubleshoot\custom-template-general.log
    // Relative path of custom_template: /troubleshoot/custom-template-general
    $this->logFilePathGeneral = $this->logDirectory.DIRECTORY_SEPARATOR.'troubleshoot'.DIRECTORY_SEPARATOR.'custom-template-general.log';

    if(!file_exists($this->logFilePathGeneral)){
      // touch($this->logFilePathGeneral);
      file_put_contents($this->logFilePathGeneral, null);
    }

  }

  public function write_log_general($logVariable): bool
  {
    if(!is_file($this->logFilePathGeneral)) {
      return false;
    }
    // If not a string, parse a var_dump result
    $logMessage = function($item){
      if(is_string($item)){
        return $item;
      }
      ob_start();
      var_dump($item);
      return ob_get_clean();
    };

    error_log(date("d-m-Y, H:i:s") . " --> Start logging variable ...  " . PHP_EOL,
              3,  $this->logFilePathGeneral);
    error_log(date("d-m-Y, H:i:s") . " \$logMessage : " . print_r($logMessage($logVariable), true) . "\n",
              3,  $this->logFilePathGeneral);
    error_log(date("d-m-Y, H:i:s") . " --> End of logging variable ...  " . PHP_EOL,
      3,  $this->logFilePathGeneral);

    return true;
  }

  public function write_log_simple($logVariable): bool
  {
    if(!is_file($this->logFilePathGeneral)) {
      return false;
    }
    // If not a string, parse a var_dump result
    $logMessage = function($item){
      if(is_string($item)){
        return $item;
      }
      ob_start();
      var_dump($item);
      return ob_get_clean();
    };

    error_log(
      sprintf("%s : %s \n",date("d-m-Y, H:i:s"), print_r($logMessage($logVariable), true)),
              3, $this->logFilePathGeneral);

    return true;
  }


}

endif;

/*
require_once('content_generator_custom_toc.php');

//$test = new ReflectionClass('Content_Generator_Custom_TOC'); // OK with variable type
$test = '12345';
$debugger = new Custom_Template_Debugger();
echo $debugger->logFilePathGeneral;
$debugger->custom_template_write_log($test);*/

/*$debugger = new Custom_Theme_Debugger();
echo $debugger->logDirectory.PHP_EOL;
echo $debugger->logFilePathGeneral.PHP_EOL;*/

