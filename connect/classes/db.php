<?php
// Разрешаем запускать только в связке с системой
if(!defined('CMS')) {
  exit();
}

class db {

  public $dbc;

  public function dbConnect($dbc) {
    $this->dbc = $dbc;
  }

  public function query($sql) {
    if (isset($this->dbc)) {
      $this->dbc->query('SET names utf8');
      $query = $this->dbc->query($sql);
      return $query;
    } else {
      return FALSE;
    }
  }
  
  public function real_escape_string($value) {
      $value =$this->dbc->real_escape_string($value);
      return $value;
  }

}

?>
