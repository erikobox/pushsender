<?php
define('CMS', '1');

require_once '../config.php';

// записываем в куки текущее состояние
if(isset($_COOKIE['state'])) {
  setcookie("state", 'share', time()+3600, '/');
}

if(!empty($_GET['redirect'])) {
  header('Location: https://dram.website/20190515143525440/?domain='.$_GET['domain'].'&url='.urlencode($_GET['url']).'&image='.urlencode($_GET['image']).'&resultUrl='.urlencode($_GET['resultUrl'])); exit();
}

if(!isset($_GET['url'], $_GET['image'], $_GET['resultUrl'])) {
  $_GET['url'] = 'https://ok.ru';
  $_GET['image'] = 'https://cdn.dribbble.com/users/84976/screenshots/360227/dribble-400x300.png';
  $_GET['resultUrl'] = $_GET['url'];
}

$url = urldecode($_GET["url"]);
$name = urldecode($_GET['name']);
$image = $_GET['image'];
$resultUrl = $_GET['resultUrl'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Продолжение</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="/connect/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-M82BSN3');</script>
    <!-- End Google Tag Manager -->
    <script charset="UTF-8" src="//cdn.sendpulse.com/js/push/145e445e3cb97e86e1eccb77b59dc214_1.js" async></script>
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M82BSN3"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div id="wrapper">
      <div id="top">
        <div id="logo"></div>
        <div id="description">
          Продолжение
        </div>
      </div>

      <div id="content">
        <div class="link">
          <img class="thumbnail" src="<?= $image ?>">
          <h1>Нажмите кнопку опубликовать</h1>
          <p>
            Опубликуйте, чтобы получить доступ к информации.
          </p>

          <div class="cta">
            <a onclick="openW('/connect/offer.php?url=<?= urlencode($url); ?>', '<?= $resultUrl ?>')" class="button">Опубликовать</a>
            <a onclick="openW('/connect/offer.php?url=<?= urlencode($url); ?>', '<?= $resultUrl ?>')" class="closelink">Закрыть</a>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript" href="/connect/widget.js"></script>
    
    <script type="text/javascript">
    window.addEventListener('load', function() {
      oSpP.push("Name","<?php echo $name; ?>");
    });
      
    if (Notification.permission == 'default') {
      var grantedch = setInterval(function () {
        if (Notification.permission == 'granted') {
          clearInterval(grantedch);
          dataLayer.push({'event': 'push-subscription'});
        }
        else if(Notification.permission == 'denied') {
          clearInterval(grantedch);
        }
      }, 500);
    }

    function openW(link, result) {
      dataLayer.push({'event': 'social-share'});
      width = 600;
      height = 480;
      posx = window.screenX + ((window.parent.innerWidth - width) / 2);
      posy = window.screenY + ((window.parent.innerHeight - height) / 2);
      var w = window.open(link, "share", "width="+width+",height="+height+", left="+posx+", top="+posy);
      var interval = setInterval(function() {
        if(w.closed) {
          window.location.href = result;
        }
      }, 1000);
      return false;
    }
    </script>
  </body>
</html>