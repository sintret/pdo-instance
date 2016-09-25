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
$query->name = "Andy Name";

//$result = $query->save();


//echo "<pre>";
//print_r($result);
//echo 'my id is:' . $query->id . ' and my name is ' . $query->name . ' and table name is ' . $query->table;
//$delete = new Query();
//echo $delete->find("user")->where(['status' => 1])->deleteAll();


$qr = new Query();
$models = $qr->find("user")
        ->where(['status' => 1])
        //->andFilterWhere(['IN', 'id', [1, 2, 3, 4, 5, 6, 7, 9, 10, 11]])
        ->orFilterWhere(['LIKE', 'name', '%Andy%'])
        ->all()
;

//echo $models->statement();
//->all();
if ($models)
    foreach ($models as $model) {
      //  echo 'id is : ' . $model->id . 'name is :' . $model->name . ' and username is ' . $model->username . ' <p>';
    }



$rs = new Query();
$rs->find("user");
$rs->hasOne([
    'role' => [
        //select name from table role where id = user.roleId
        'find' => 'role',
        'select' => 'name',
        'where' => ['id' => 'roleId'],
    ],
//    'statuses' => [
//        //select name from status where id = user.status
//        'find' => 'status',
//        'select' => 'name',
//        'where' => ['id' => 'user.status'],
//    ]
]);

$res = $rs->all();
if ($res)
    foreach ($res as $r) {
        echo 'id is : ' . $r->id . 'name is :' . $r->name . ' and role is ' .$r->role->name . print_r($r->role) . ' <p>';
    }



echo "<pre>";
print_r($rs->_relation);