<?php

use Melicerte\WordPredictor\WordPredictor;

require_once '../vendor/autoload.php';

$wordPredictor = new WordPredictor();
$dir = "../";
$datas = strip_tags(file_get_contents($dir . "recherches.txt"));
$wordPredictor->addDocument($datas);

header('Content-Type: application/json');
echo json_encode($wordPredictor->getPredictions($_REQUEST['search']));
exit;
