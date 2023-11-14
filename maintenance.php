<?php
/*
  Store Mode 1.6.0
  by @raiwa
  raiwa@phoenixcartaddons.com
  www.phoenixcartaddons.com
  
  updated for Phoenix Pro by @ecartz

  Copyright (c) 2021, Rainer Schmied
  All rights reserved.

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License

  This is the custom error 503 "Service Temporarily Unavailable" page.
  The 503 redirect code is in the main .htaccess file.
*********************************************************************************************************************************
  NOTE: Do not add any external resources like images/store logo to this page.
  This page must not require any additional resource of the store installation to ensure it will show correct in any situation.
*********************************************************************************************************************************
*/

// EDIT BEGIN

  //uncomment additional languages
  const TEXT_MAINTENANCE_MESSAGE_ENGLISH = 'We are currently installing some upgrades to enhance your shopping experience.<br><br>We apologize for any inconvenience this may cause.<br><br>Thank you for your understanding.';
  const TEXT_MAINTENANCE_REOPEN_MESSAGE_ENGLISH = '<br><br>We plan to reopen this area in aprox. %s hours.';
  const TEXT_MAINTENANCE_CONTACT_ENGLISH = '<br><br><a href="mailto: %s ?Subject=Store Offline" target="_top"><input type="button" class="button" value="Contact"></a>';
  const TEXT_MAINTENANCE_STATUS_ENGLISH = '<br><br>To verify the site status ... Click Continue<br><br>';
  const TEXT_MAINTENANCE_MESSAGE_ESPANOL = 'Actualmente estamos instalando algunas actualizaciones para mejorar su experiencia de compra.<br><br>Pedimos disculpas por cualquier inconveniente que esto pueda causar.<br><br> Gracias por su comprensión.';
  const TEXT_MAINTENANCE_REOPEN_MESSAGE_ESPANOL = '<br><br>Planeamos volver a abrir dentro de aprox. %s horas';
  const TEXT_MAINTENANCE_CONTACT_ESPANOL = '<br><br><a href="mailto: %s ?Subject=Tienda Cerrada" target="_top"><input type="button" class="button" value="Contactar"></a>';
  const TEXT_MAINTENANCE_STATUS_ESPANOL = '<br><br>Para comprobar el estado del sitio ... Haga clic en Continuar<br><br>';
  const TEXT_MAINTENANCE_MESSAGE_GERMAN = 'Wir installieren derzeit einige Upgrades, um Ihr Einkaufserlebnis zu verbessern.<br><br>Wir entschuldigen uns für eventuelle Unannehmlichkeiten.<br><br>Danke für Ihr Verständnis.';
  const TEXT_MAINTENANCE_REOPEN_MESSAGE_GERMAN = '<br><br>Wir planen in etwa %s Stunden wieder zu öffnen';
  const TEXT_MAINTENANCE_CONTACT_GERMAN = '<br><br><a href="mailto: %s ?Subject=Shop Offline" target="_top"><input type="button" class="button" value="Kontakt"></a>';
  const TEXT_MAINTENANCE_STATUS_GERMAN = '<br><br>Zum überprüfen des Site-Status ... Klicken Sie bitte auf Weiter<br><br>';

// EDIT END

// OPTIONAL EDIT - you can change the store name and e-mail if you wish to use something different from your configuration settings.
// change once the store mode header tag module is installed, changes will be overwritten if you install again.
  const TEXT_STORE_NAME = 'Store Mode'; // your store name should be auto filled in on installing the store mode header tag module

  $store_mail = 'info@example.com'; // your store mail should be auto filled in on installing the store mode header tag module. Comment to disable Mail

  date_default_timezone_set(date_default_timezone_get()); // your store time zone should be auto filled in on installing the store mode  module.

  $back_time = empty($_GET['back_time']) ? date('H:i', strtotime('now +2 hour')) : $_GET['back_time'];

  $time_left = null;

  if ( isset($back_time) && $back_time != '') {
    $to_time = strtotime(date("Y-m-d H:i:s"));
    $from_time = strtotime(date('Y-m-d') . $back_time . ':00');
    if ($from_time < $to_time) $from_time += 24 * 60 * 60;
    $time_left = gmdate("H:i", round(abs($to_time - $from_time)));
  }

  $protocol = "HTTP/1.0";
  if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"]) {
    $protocol = "HTTP/1.1";
    header("$protocol 503 Service Unavailable", true, 503);
	  header('Status: 503 Service Temporarily Unavailable');
    header("Retry-After: 3600");
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en-US">
<head>
<title>503 - Service Temporarily Unavailable</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="HandheldFriendly" content="true">
<style type="text/css">
body {
background-color:#cbcbcb;
font-size:18px;
font-family:Arial, Helvetica, sans-serif;
}
.container {
  -moz-border-radius:10px;
  -webkit-border-radius:10px;
  -khtml-border-radius:10px;
  -opera-border-radius:10px;
  border-radius:10px;
  background-color:#fff;
  max-width:600px;
  margin:20px auto;
  padding:20px 20px 25px 30px;
  color:#3b3a3a;
  text-align:center;
}
h1{
  margin:0px;
  color:#626262;
  font-size:25px;
  font-weight:bold;
  text-shadow:2px 2px 4px #D8D8D8;
}
.button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
}
</style>
</head>
<body>
  <div class="container rounded-corners">
    <h1><?= TEXT_STORE_NAME ?></h1>
  </div>
<?php
if ( defined('TEXT_MAINTENANCE_MESSAGE_ENGLISH') && TEXT_MAINTENANCE_MESSAGE_ENGLISH != '' ) {
  ?>
  <div class="container">
    <?php
    echo TEXT_MAINTENANCE_MESSAGE_ENGLISH;
    if ( defined('TEXT_MAINTENANCE_REOPEN_MESSAGE_ENGLISH') && TEXT_MAINTENANCE_REOPEN_MESSAGE_ENGLISH != '' && $time_left != '' ) {
      printf(TEXT_MAINTENANCE_REOPEN_MESSAGE_ENGLISH, $time_left);
    }
    if ( defined('TEXT_MAINTENANCE_CONTACT_ENGLISH') && TEXT_MAINTENANCE_CONTACT_ENGLISH != '' && $store_mail != '' ) {
      printf(TEXT_MAINTENANCE_CONTACT_ENGLISH, $store_mail);
    }
    if ( defined('TEXT_MAINTENANCE_STATUS_ENGLISH') && TEXT_MAINTENANCE_STATUS_ENGLISH ) {
      echo  TEXT_MAINTENANCE_STATUS_ENGLISH;
    ?>
    <form>
      <input type="button"  class="button" value="Continue" onclick="window.location.href='<?= dirname($_SERVER["REQUEST_URI"])."/index.php?language=en" ?>'" />
    </form>
    <?php
    }
    ?>
  </div>
<?php
}
if (defined('TEXT_MAINTENANCE_MESSAGE_ESPANOL') && TEXT_MAINTENANCE_MESSAGE_ESPANOL != '' ) {
  ?>
  <div class="container">
    <?php
    echo TEXT_MAINTENANCE_MESSAGE_ESPANOL;
    if ( defined('TEXT_MAINTENANCE_REOPEN_MESSAGE_ESPANOL') && TEXT_MAINTENANCE_REOPEN_MESSAGE_ESPANOL != '' && $time_left != '' ) {
      printf(TEXT_MAINTENANCE_REOPEN_MESSAGE_ESPANOL, $time_left);
    }
    if ( defined('TEXT_MAINTENANCE_CONTACT_ESPANOL') && TEXT_MAINTENANCE_CONTACT_ESPANOL != '' && $store_mail != '' ) {
      printf(TEXT_MAINTENANCE_CONTACT_ESPANOL, $store_mail);
    }
    if ( defined('TEXT_MAINTENANCE_STATUS_ESPANOL') && TEXT_MAINTENANCE_STATUS_ESPANOL ) {
      echo  TEXT_MAINTENANCE_STATUS_ESPANOL;
    ?>
    <form>
      <input type="button"  class="button" value="Continuar" onclick="window.location.href='<?= dirname($_SERVER["REQUEST_URI"])."/index.php?language=es" ?>'" />
    </form>
    <?php
    }
    ?>
  </div>
<?php
}
if (defined('TEXT_MAINTENANCE_MESSAGE_GERMAN') && TEXT_MAINTENANCE_MESSAGE_GERMAN != '' ) {
  ?>
  <div class="container">
    <?php
    echo TEXT_MAINTENANCE_MESSAGE_GERMAN;
    if ( defined('TEXT_MAINTENANCE_REOPEN_MESSAGE_GERMAN') && TEXT_MAINTENANCE_REOPEN_MESSAGE_GERMAN != '' && $time_left != '' ) {
      printf(TEXT_MAINTENANCE_REOPEN_MESSAGE_GERMAN, $time_left);
    }
    if ( defined('TEXT_MAINTENANCE_CONTACT_GERMAN') && TEXT_MAINTENANCE_CONTACT_GERMAN != '' && $store_mail != '' ) {
      printf(TEXT_MAINTENANCE_CONTACT_GERMAN, $store_mail);
    }
    if ( defined('TEXT_MAINTENANCE_STATUS_GERMAN') && TEXT_MAINTENANCE_STATUS_GERMAN ) {
      echo  TEXT_MAINTENANCE_STATUS_GERMAN;
    ?>
    <form>
      <input type="button"  class="button" value="Weiter" onclick="window.location.href='<?= dirname($_SERVER["REQUEST_URI"])."/index.php?language=de" ?>'" />
    </form>
    <?php
    }
    ?>
  </div>
<?php
}
?>
</body>
</html>