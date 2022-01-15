<?php
$jsonArray = [];
 
//Если файл существует - получаем его содержимое
if (file_exists('db.json')){
    $json = file_get_contents('db.json');
    $jsonArray = json_decode($json, true);
	unset($json);
}
?>