<?php
// Разрешаем запускать только в связке с системой
if(!defined('CMS')) {
  exit();
}

class database {

  private $db;

  public function __construct($host, $user, $pass, $base) {
    $this->db = new mysqli($host, $user, $pass, $base);
    
    // проверка соединения
    if ($this->db->connect_errno) {
      printf("Connect failed: %s\n", $this->db->connect_error);
      exit();
    }
  }
  
  public function query($sql) {
    $result = $this->db->query($sql);
    return $result;
  }
  
  public function last_id() {
    $last_id = $this->db->insert_id;
    return $last_id;
  }
}

?>
