<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

require_once './facebook_sdk/src/facebook.php';
require_once './FacebookApp.php';

define('FACE_APP_ID', '369427636414937');
define('FACE_APP_SECRET', '6ccddd805af4ca4e2eaa19a5a150d91f');

$conn = new \PDO("mysql:host=localhost;dbname=phpbrasil", "root", "");
$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(1002, 'SET NAMES utf8');
$conn->exec("set names utf8");

$sql = "SELECT * FROM post ORDER BY datacadastro DESC limit 1";
$result = $conn->query($sql);
$objeto = $result->fetchObject();
$dtLastInsert = new DateTime($objeto->datacadastro);


function store($feeds){
    foreach (array_reverse($feeds['data']) as $post) {
        global $conn;
        global $dtLastInsert;
        
        $date = new DateTime($post['updated_time']);
        $dateinsert = $date->format('Y-m-d H:i:s');
        
        if($date < $dtLastInsert){
            header('location: atualizado.php');
            exit;
        }
        
        $stmte = $conn->prepare("INSERT INTO post(nome, post, idpost,datacadastro) VALUES 
											(:nome, :post, :idpost,:datacadastro)");

        $stmte->bindParam(":nome", $post['from']['name'], \PDO::PARAM_STR);
        $stmte->bindParam(":post", $post['message'], \PDO::PARAM_STR);
        $stmte->bindParam(":idpost", $post['id'], \PDO::PARAM_STR);
        $stmte->bindParam(":datacadastro", $dateinsert, \PDO::PARAM_STR);
        
        $executa = $stmte->execute();

        if (is_array($post['comments']['data'])) {
            foreach ($post['comments']['data'] as $comment) {
                $stmte = $conn->prepare("INSERT INTO comment(message, from_name, from_id, idcomment, idpost) VALUES 
											(:message, :from_name, :from_id, :idcomment, :idpost)");

                
                $stmte->bindParam(":message", $comment['message'], \PDO::PARAM_STR);
                $stmte->bindParam(":from_name", $comment['from']['name'], \PDO::PARAM_STR);
                $stmte->bindParam(":from_id", $comment['from']['id'], \PDO::PARAM_STR);
                $stmte->bindParam(":idcomment", $comment['id'], \PDO::PARAM_STR);
                $stmte->bindParam(":idpost", $post['id'], \PDO::PARAM_STR);
                $executa = $stmte->execute();
            }
        }
    }
}
//session_destroy();
//var_dump($_SESSION['query']);
//exit;

if (!isset($_SESSION['query'])) {
    $face = new FacebookApp();
    $face->setAccessToken(FACE_APP_ID . '|' . FACE_APP_SECRET);
    $feeds = $face->getFeed('14811750159');
    store($feeds);
    
    $parse = parse_url($feeds['paging']['next']);
    $_SESSION['query'] = $parse['query'];
    
} else {
    $face = new FacebookApp();
    $face->setAccessToken(FACE_APP_ID . '|' . FACE_APP_SECRET);
    $aQuery = explode('&', $_SESSION['query'] );
    $feeds = $face->getFeed('14811750159', $aQuery[2]);
    store($feeds);
    
    $parse = parse_url($feeds['paging']['next']);
    $_SESSION['query'] = $parse['query'];
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
        <meta http-equiv="refresh" content="1">
    </head>
    <body>
        <h2>Enviando...</h2>
    </body>
</html>