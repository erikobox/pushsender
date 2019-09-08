<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// защита от прямого запуска вложенных скритов
define("CMS", 1);
define("ROOT", dirname(__FILE__));

// инициализация нужных настроек и библиотек
require_once ROOT . '/config.php'; // файл настроек
require_once ROOT . '/classes/db.php'; // класс базы данных
$dbc = new database($host, $user, $pass, $base); // подключаем базу данных

require_once ROOT . '/classes/tpl.php'; // класс шаблонизатора
$tpl = new tpl();

require_once ROOT . '/classes/users.php'; // класс страницы
$user = new user($dbc, $tpl);

require_once ROOT . '/classes/campaigns.php'; // класс кампании
$campaign = new campaign($dbc, $tpl);

// проверка POST параметров
if(isset($_POST['action'])) {
  if($_POST['action'] == 'signup' && isset($_POST['login'], $_POST['password'], $_POST['email'])) {
    $user->registration($_POST['login'], $_POST['password'], $_POST['email']);
  }
  else if($_POST['action'] == 'enter' && isset($_POST['login'], $_POST['password'])) {
    $user->enter($_POST['login'], $_POST['password']);
  }
  else if($_POST['action'] == 'logout') {
    setcookie("user", '');
    header("Location: /index.php");
  }
  else if($_POST['action'] == 'newcampaign' && isset($_POST['name'], $_POST['afflink'], $_POST['interest'], $_COOKIE['user'])) {
    $campaign->create_campaign($_COOKIE['user'], $_POST['name'], $_POST['afflink'], $_POST['interest']);
  }
  else if($_POST['action'] == 'newlanding' && isset($_POST['campaignid'], $_POST['link'], $_POST['title'], $_POST['text'], $_FILES["thumbnail"], $_FILES["bigimage"], $_POST['step'], $_POST['starttime'])) {
    $campaign->create_landing($_POST['campaignid'], $_POST['title'], $_POST['text'], $_POST['link'], $_FILES["thumbnail"], $_FILES["bigimage"], $_POST['step'], $_POST['starttime']);
  }
  else {
    // ввели неправльные данные
    //header('Location: /index.php');
  }
            
  exit;
}

// проверка GET параметров
if(isset($_GET['campaign']) || isset($_GET['landing']) && isset($_COOKIE['user'])) {
  if(isset($_GET['campaign']) && $_GET['campaign'] == 'new') {
    $campaign->newCompaign($_COOKIE['user']);
  }
  else if(isset($_GET['campaign']) && $_GET['campaign'] == 'list') {
    $campaign->allCompaign($_COOKIE['user']);
  }
  else if(isset($_GET['campaign']) && !isset($_GET['landing'])) {
    $campaign->getCampaign($_GET['campaign']);
  }
  
  if(isset($_GET['landing']) && $_GET['landing'] == 'new') {
    $campaign->newLanding($_GET['campaign']);
  }
  else if(isset($_GET['landing'])) {
    $campaign->getLanding($_GET['landing']);
  }
}
else if(isset($_GET['action']) && !isset($_COOKIE['user'])) {
  if($_GET['action'] == 'signup') {
    $user->signup();
  }
}
else {
  // Проверяем куки
  if(isset($_COOKIE['user'])) {
    $user->dashboard($_COOKIE['user']);
  }
  else {
    $user->signin();
  }
}
?>