<?php

ini_set("dispaly_errors", 1);
include 'Query.php';




/*
 * select * from user where roleId = 3
 * with all data
 * just like this following code
 */

$query = new Query;
$query->find("user")
        ->where(['roleId' => 3])
        ->limit(2)
        ->orderBy("id desc");

$result = $query->all();

//print result
echo "<pre>";
print_r($result);

//print result single row using one()
$one = $query->one();
echo "<pre>";
print_r($one);



// looping
if ($result)
    foreach ($result as $res) {
        echo ' id :' . $res->id . ' username is :' . $res->username;
    }

echo "<p>&nbsp;</p>";
// insert into

$query = new Query;
$query->create('user');
$query->name = "Andy Laser";
$query->email = 'my_email@gmail.com';
$query->username = "laser";
$query->save();

echo "<pre>";print_r($query);



echo 'update date is :' . $query->updateDate . ' and table name is ' . $query->table;
