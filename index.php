<?php
date_default_timezone_set('America/Sao_Paulo');

$conn = new \PDO("mysql:host=localhost;dbname=phpbrasil", "root", "58347105");
$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(1002, 'SET NAMES utf8');
$conn->exec("set names utf8");

$sql = "SELECT * FROM post ORDER BY datacadastro DESC limit 300";
$result = $conn->query($sql);

while ($objeto = $result->fetchObject()) {
    $aObjetos[] = $objeto;
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
    </head>
    <body>
        <h2>Perguntas</h2>
        <ul>
            <?php
            foreach ($aObjetos as $obj) {
                echo "<li>" . htmlspecialchars($obj->post) . "</li>";
                $sql = "SELECT * FROM comment WHERE idpost = '{$obj->idpost}'";
                $result = $conn->query($sql);

                echo '<li><ul>';
                while ($objeto = $result->fetchObject()) {
                    echo "<li>".  htmlspecialchars($objeto->message)."</li>";
                }
                echo '</li></ul>';
            }
            ?>
        </ul>
    </body>
</html>