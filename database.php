<?php

ini_set("dispaly_errors", 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'Model.php';
$query = new Model();

//$query->find('article')->where(['id' => 8]);

$query->find('article');
//$query->where(['id' => 8]);
$r = $query->all();

echo "<pre>";
print_r($r);


echo $r->id;

echo "test";
