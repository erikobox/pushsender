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

require_once ROOT . '/classes/notifications.php'; // класс страницы
$notification = new notification($dbc);
$id = $notification->findMessage($timezone);

if($id == 'error') {
  echo 'error';
  exit();
}

require 'vendor/autoload.php';

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

// API credentials from https://login.sendpulse.com/settings/#api
define('API_USER_ID', $apiUserId);
define('API_SECRET', $apiSecret);
define('PATH_TO_ATTACH_FILE', __FILE__);

$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

$notification->getMessage($id);

$interest = $notification->interest;
$campaign = $notification->campaign;
$landing = $notification->link;
$step = $notification->step;
$prevStep = $step - 1;
$nextStep = $step + 1;
$title = $notification->title;
$text = $notification->text;
$thumbnail = $notification->convertImage($notification->thumbnail);
$bigimage = $notification->convertImage($notification->bigimage);

$task = array(
    'title' => $title,
    'body' => $text,
    'website_id' => $wesiteid,
    'ttl' => 1,
    'stretch_time' => 0,
);

$additionalParams = array(
  'link' => $website . '/link.php?id=' . $id,
  'icon' => '{"name":"icon.jpg","data":"'.$thumbnail.'"}',
  'image' => '{"name":"image.jpg","data":"'.$bigimage.'"}'
);

if($prevStep > 0) {
  $additionalParams = array_merge($additionalParams, array(
    'filter' => '{"variable_name":"Campaign'.$campaign.'step","operator":"and","conditions":[{"condition":"likewith","value":"'.$prevStep.'"}]}'
  ));
}
else {
  $additionalParams = array_merge($additionalParams, array(
    'filter' => '{ "variable_name": "Campaign'.$campaign.'", "operator": "or", "conditions": [ { "condition": "notlikewith", "value": "'.$campaign.'" } ] }'
  ));
}

var_dump($SPApiClient->createPushTask($task, $additionalParams));
?>