<?php

if(!defined('CMS')) {
  exit();
}

class notification {
  private $db;
  public $interest;
  public $campaign;
  public $link;
  public $title;
  public $text;
  public $step;
  public $thumbnail;
  public $bigimage;
  
  public function __construct($dbc) {
    $this->db = $dbc;
  }
  
  public function getMessage($id) {
    $result = $this->db->query("SELECT * FROM landings WHERE id=$id LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    
    if(!empty($row)) {
      $this->link = $row['link'];
      $this->title = $row['title'];
      $this->text = $row['text'];
      $this->step = $row['step'];
      $this->campaign = $row['campaign'];
      $this->thumbnail = $row['thumbnail'];
      $this->bigimage = $row['bigimage'];
      
      $result_cid = $this->db->query("SELECT * FROM campaigns WHERE id=$this->campaign LIMIT 1");
      $row_cid = mysqli_fetch_assoc($result_cid);
      $this->interest = $row_cid['interest'];
    }
  }
  
  public function findMessage($timezone) {
    $starttime = date('H:i', strtotime("now $timezone GMT"));
    
    $result = $this->db->query("SELECT id FROM landings WHERE starttime <= '$starttime:00' and endtime >= '$starttime:00' LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    
    if(!empty($row)) {
      return $row['id'];
    }
    else {
      return 'error';
    }
  }
  
  public function convertImage($path) {
    $image = file_get_contents($path);
    $image = base64_encode($image);
    return $image;
  }
}