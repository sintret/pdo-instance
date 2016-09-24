<?php

ini_set("dispaly_errors", 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'Query.php';
$query = new Query();
$query->find("user");
$query->username = 'my_username';
$query->email = 'my_email@gmail.com';
$query->status = 1;
$query->name = "My Name";

$result = $query->save();


echo "<pre>";
print_r($result);

//echo 'my id is:' . $query->id . ' and my name is ' . $query->name . ' and table name is ' . $query->table;


//$delete = new Query();
//echo $delete->find("user")->where(['status' => 1])->deleteAll();


$qr = new Query();
$models = $qr->find("user")
        ->where(['status'=>1])
        ->andFilterWhere(['LIKE', 'name', '%Andy%'])
        ->all();
if ($models)
    foreach ($models as $model) {
        echo 'name is :' . $model->name . ' and username is ' . $model->username . ' <p>';
    }


echo $qr->statement();

echo "<br>";

print_r($models);
print_r($qr->arrayWhere);

