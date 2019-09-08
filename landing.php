<?php
// защита от прямого запуска вложенных скритов
define("CMS", 1);
define("ROOT", dirname(__FILE__));

if(isset($_GET['link'], $_GET['campaign'])) {
  $url = $_SERVER["SCRIPT_NAME"];
  $break = Explode('/', $url);
  $file = $break[count($break) - 1];
  $cachefile = 'cache/landings/'.urlencode($_GET['link']).'.html';

  // Обслуживается из файла кеша, если время запроса меньше $cachetime
  if (file_exists($cachefile)) {
      include($cachefile);
      exit;
  }
  ob_start(); // Запуск буфера вывода
  
  // инициализация нужных настроек и библиотек
  require_once ROOT . '/config.php'; // файл настроек
  require_once ROOT . '/classes/db.php'; // класс базы данных
  $dbc = new database($host, $user, $pass, $base); // подключаем базу данных

  require_once ROOT . '/classes/users.php'; // класс страницы
  $user = new user($dbc, $tpl);

  require_once ROOT . '/classes/campaigns.php'; // класс кампании
  $campaign = new campaign($dbc, $tpl);
  
  $url = parse_url($_GET['link']);
  
  $link = $campaign->getAffLink($_GET['campaign']);
  
  require_once('classes/simple_html_dom.php');

  $html = file_get_html($_GET['link']);
  if ($html){ // Verify connection, return False if could not load the resource
  $e = $html->find("a");
  foreach ($e as $e_element){
      $old_href = $e_element->outertext;
      // Do your modification in here 
      //$e_element->href = affiliate($e_element->href); // for example I replace original link by the return of custom function named 'affiliate'
      $e_element->href = $link; //remove href
      $e_element->target .= "_blank"; // I added target _blank to open in new tab
      // end modification 
      $html = str_replace($old_href, $e_element->outertext, $html); // Update the href
  }
  }

  $code = (string) $html;
  
  print_r($code);
  
  // Кешируем содержание в файл
  $cached = fopen($cachefile, 'w');
  fwrite($cached, ob_get_contents());
  fclose($cached);
  ob_end_flush(); // Отправялем вывод в браузер
}
