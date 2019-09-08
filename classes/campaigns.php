<?php

if(!defined('CMS')) {
  exit();
}

class campaign {
  private $db;
  private $tpl;
  
  public function __construct($dbc, $tpl) {
    $this->db = $dbc;
    $this->tpl = $tpl;
  }
  
  public function create_campaign($user, $name, $afflink, $interest) {
    $this->db->query("INSERT INTO campaigns (user, name, interest) VALUES ($user, '$name', '$afflink', '$interest')");
    $last_id = $this->db->last_id();
    header("Location: /index.php?campaign=$last_id");
  }
  
  public function getAffLink($id) {
    $result = $this->db->query("SELECT afflink FROM campaigns WHERE id=$id LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    
    if(!empty($row)) {
      return $row['afflink'];
    }
    else {
      return 'error';
    }
  }
  
  private function uploadimage($target_dir, $image) {
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($image["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($image["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            echo "The file ". basename( $image["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
  }
  
  public function create_landing($campaign, $title, $text, $link, $thumbnail, $bigimage, $step, $starttime) {
    $endtime = strtotime($starttime) + 1200;
    $endtime = date('H:i', $endtime);
    
    // подготовка и загрузка картинки на сервер
    $this->uploadimage("uploads/thumbnail/", $thumbnail);
    $this->uploadimage("uploads/bigimages/", $bigimage);
    
    $this->db->query("INSERT INTO landings (campaign, link, title, text, thumbnail, bigimage, step, starttime, endtime) VALUES ($campaign, '$link', '$title', '$text', 'uploads/thumbnail/$thumbnail[name]', 'uploads/bigimages/$bigimage[name]', $step, '$starttime', '$endtime')");
    $last_id = $this->db->last_id();
    header("Location: /index.php?landing=$last_id");
  }
  
  public function newCompaign($user) {
    $this->tpl->tpl_load('newcampaign');
    $this->tpl->set('{userid}', $user);
    $newcampaign = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Новая кампания');
    $this->tpl->set('{content}', $newcampaign);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function allCompaign($user) {
    $result = $this->db->query("SELECT * FROM campaigns WHERE user=$user");
    $list = '<ul>';
    while($row = mysqli_fetch_assoc($result)) {
      $list .= '<li><a href="/index.php?campaign='.$row['id'].'">'.$row['name'].'</a></li>';
    }
    $list .= '</ul>';
    
    $this->tpl->tpl_load('allcampaign');
    $this->tpl->set('{campaigns}', $list);
    $allcampaign = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Все кампании');
    $this->tpl->set('{content}', $allcampaign);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function getCampaign($id) {
    $result = $this->db->query("SELECT * FROM landings WHERE campaign=$id");
    $list = '<ul>';
    while($row = mysqli_fetch_assoc($result)) {
      $list .= '<li><a href="/index.php?landing='.$row['id'].'">'.$row['title'].'</a></li>';
    }
    $list .= '</ul>';
    
    $this->tpl->tpl_load('campaign');
    $this->tpl->set('{landings}', $list);
    $this->tpl->set('{campaignid}', $id);
    $alllandings = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Все лендинги кампании');
    $this->tpl->set('{content}', $alllandings);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function newLanding($campaign) {
    $this->tpl->tpl_load('newlanding');
    $this->tpl->set('{campaignid}', $campaign);
    $newlanding = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Новый лендинг');
    $this->tpl->set('{content}', $newlanding);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function getLanding($id) {
    $result = $this->db->query("SELECT * FROM landings WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    
    $this->tpl->tpl_load('landing');
    
    // тут переменные лендинга
    $this->tpl->set('{campaignid}', $row['campaign']);
    $this->tpl->set('{link}', $row['link']);
    $this->tpl->set('{title}', $row['title']);
    $this->tpl->set('{text}', $row['text']);
    $this->tpl->set('{thumbnail}', $row['thumbnail']);
    $this->tpl->set('{bigimage}', $row['bigimage']);
    $this->tpl->set('{step}', $row['step']);
    $this->tpl->set('{starttime}', $row['starttime']);
    $this->tpl->set('{endtime}', $row['endtime']);
    $this->tpl->set('{clicks}', $row['clicks']);
    
    $landing = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', $row['title']);
    $this->tpl->set('{content}', $landing);
    $html = $this->tpl->compile();
    echo $html;
  }
}