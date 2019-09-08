<?php
// защита от прямого запуска вложенных скритов
define("CMS", 1);
define("ROOT", dirname(__FILE__));

if(!isset($_GET['id'])) {
  exit();
}

// инициализация нужных настроек и библиотек
require_once ROOT . '/config.php'; // файл настроек
require_once ROOT . '/classes/db.php'; // класс базы данных
$dbc = new database($host, $user, $pass, $base); // подключаем базу данных

require_once ROOT . '/classes/notifications.php'; // класс страницы
$notification = new notification($dbc);
$notification->getMessage($_GET['id']);

$interest = $notification->interest;
$campaign = $notification->campaign;
$landing = $notification->link;
$query = parse_url($landing, PHP_URL_QUERY);

// Returns a string if the URL has parameters or NULL if not
if ($query) {
    $landing .= '&campaign='.$campaign;
} else {
    $landing .= '?campaign='.$campaign;
}

$step = $notification->step;
$prevStep = $step - 1;
$title = $notification->title;
$text = $notification->text;
?>

<html>
  <head>
    <title></title>
    <script charset="UTF-8" src="//cdn.sendpulse.com/js/push/145e445e3cb97e86e1eccb77b59dc214_1.js" async></script>
  </head>
  <body>
    <script type="text/javascript">
    window.addEventListener('load', function() {
      oSpP.push("<?= $interest; ?>","<?= $interest; ?>");
      oSpP.push("Campaign<?= $campaign; ?>","<?= $campaign; ?>");
      oSpP.push("Campaign<?= $campaign; ?>step","<?= $step; ?>");

      window.location.href = '<?= $landing; ?>';
    });
    </script>
  </body>
</html>