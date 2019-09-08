<?php

if (!defined('CMS')) {
  exit();
}

class tpl {
  private $tpl;
  private $tplset = array();
	  
  public function tpl_load($tplname) {
    if (!file_exists(ROOT . '/tpl/' . $tplname . '.tpl'))
      exit("Error");
    
    $this->tpl = file_get_contents(ROOT . '/tpl/' . $tplname . '.tpl');
  }

  public function set($key, $value) {
    $arr[$key] = $value;
    array_push($this->tplset, $arr);
  }
  
  public function compile() {
    $result = $this->tpl;
    foreach ($this->tplset as $key => $value) {
      foreach($value as $key => $value) {
	$result = str_replace($key, $value, $result);
      }
    }
    
    return $result;
  }
}
