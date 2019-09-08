<?php

if(!defined('CMS')) {
  exit();
}

class user {
  private $db;
  private $tpl;
  
  public function __construct($dbc, $tpl) {
    $this->db = $dbc;
    $this->tpl = $tpl;
  }
  
  public function signin() {
    $this->tpl->tpl_load('enter');
    $enter = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Войти');
    $this->tpl->set('{content}', $enter);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function signup() {
    $this->tpl->tpl_load('signup');
    $signup = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Регистрации');
    $this->tpl->set('{content}', $signup);
    $html = $this->tpl->compile();
    echo $html;
  }
  
  public function registration($login, $password, $email) {
    $result = $this->db->query("SELECT * FROM users WHERE login='$login' or email='$email' LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    
    if(empty($row)) {
      $this->db->query("INSERT INTO users (login, password, email) VALUES ('$login', '$password', '$email')");
      header("Location: /index.php"); // вернуть на страницу входа после регистрации
    }
    else {
      // такой пользователь уже есть в системе
      header("Location: /index.php");
    }
  }
  
  public function enter($login, $password) {
    $result = $this->db->query("SELECT * FROM users WHERE login='$login' and password='$password' LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    
    if(!empty($row)) {
      setcookie('user', $row['id']);
      header("Location: /index.php");
    }
    else {
      header("Location: /index.php");
    }
  }
  
  public function dashboard($userid) {
    $this->tpl->tpl_load('dashboard');
    $dashboard = $this->tpl->compile();
    $this->tpl->tpl_load('main');
    $this->tpl->set('{title}', 'Дашбоард');
    $this->tpl->set('{content}', $dashboard);
    $html = $this->tpl->compile();
    echo $html;
  }
}