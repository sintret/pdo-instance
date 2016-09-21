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
echo "<pre>";print_r($result);

//print result single row using one()
$one = $query->one();
echo "<pre>";print_r($one);



// looping
if ($result)
    foreach ($result as $res) {
        echo ' id :' . $res->id . ' username is :' . $res->username;
    }
