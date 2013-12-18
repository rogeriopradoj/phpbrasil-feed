<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

require_once './facebook_sdk/src/facebook.php';
require_once './FacebookApp.php';

define('FACE_APP_ID', '369427636414937');
define('FACE_APP_SECRET', '6ccddd805af4ca4e2eaa19a5a150d91f');


$face = new FacebookApp();
$face->setAccessToken(FACE_APP_ID . '|' . FACE_APP_SECRET);
$feeds = $face->getFeed('14811750159');

foreach ($feeds['data'] as $post) {
    echo htmlspecialchars($post['message']) . '<br>';
    echo "<hr>";
}

$parse = parse_url($feeds['paging']['next']);
$aQuery = explode('&',$parse['query']);

$feeds = $face->getFeed2('14811750159', $aQuery[2]);


foreach ($feeds['data'] as $post) {
    echo htmlspecialchars($post['message']) . '<br>';
    echo "<hr>";
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="pt" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="pt" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="pt" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="pt" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="pt" class="no-js"> <!--<![endif]-->
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!--<meta http-equiv="refresh" content="1">-->
    </head>
    <body>
        <h2>Enviando...</h2>
    </body>
</html>