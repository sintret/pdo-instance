<?php

ini_set("dispaly_errors", 1);

include 'Model.php';

$query = new Model();

$query->find('article');
//$query->where(['id' => 8]);
$r = $query->all();

echo "<pre>";
print_r($r);


echo $r->id;

echo "test";
