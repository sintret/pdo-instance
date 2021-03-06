PDO Instance
============================

Best instance PDO class for rapidly creating small project.


### Setup Database

Edit the file in `Db.php` with real data, for example :

```php
    protected static $_instance = null;
    protected static $username = "root";
    protected static $password = "";
    protected static $dsn = "mysql:host=localhost;dbname=test";
```

Query Programming
-------------

This class have multiple method like "find, where, limit, orderBy, statement, one, all, save, delete, deleteAll". Please see the example using this class.
Please ensure you have include 'Query.php'

### select one

This following code will get single row using one() method.
```php
$query = new Query;
$query->find("my_table")
        ->where(['status' => 1])
        ->orderBy("id desc");

//print result single row using one()
$one = $query->one();
echo "<pre>";print_r($one);

```


### select all with limit


This following code will get many row using `all` method.
```php
$query = new Query;
$query->find("user")
        ->where(['status' => 1])
        ->limit(2)
        ->orderBy("id desc");

$result = $query->all();

//print result
echo "<pre>";print_r($result);
```

### select and filter where


This following code will get many row using all() method.
```php
$qr = new Query();
$models = $qr->find("user")
        ->where(['status'=>1])
        ->andFilterWhere(['LIKE', 'name', '%Andy%'])
        ->all();
if ($models)
    foreach ($models as $model) {
        echo 'name is :' . $model->name . ' and username is ' . $model->username . ' <p>';
    }

// Search AND IN array
$qr = new Query();
$models = $qr->find("user")
        ->where(['status' => 1])
        ->andFilterWhere(['IN', 'id', [1, 2, 3, 4, 5, 6, 7, 9, 10, 11]])
        ->all()
        ;

// Search OR IN array
$qr = new Query();
$models = $qr->find("user")
        ->where(['status' => 1])
        //->andFilterWhere(['IN', 'id', [1, 2, 3, 4, 5, 6, 7, 9, 10, 11]])
        ->orFilterWhere(['LIKE', 'name', '%Andy%'])
        ->all()
;

```

### insert data

This following example how to insert data 
```php
$query = new Query;
$query->find('user');
$query->name = "Andy Laser";
$query->email = 'my_email@gmail.com';
$query->username = "laser";
$query->save();

echo "<pre>";print_r($query);

echo 'my id is:' . $query->id . ' and my name is ' . $query->name. ' and table name is ' . $query->table;

```


### update data

This following example how to update data and using where and one() method
```php
$user = new Query();
$user->find('user')->where(['id' => 12])->one();

$user->username = 'testing1123232';
$user->save();

echo 'my id is:' . $query->id . ' and my name is ' . $query->name . ' and table name is ' . $query->table;
```


### delete data

This following example how to delete using delete() or deleteAll(). 
Callback value 1 for success or 0 not success
```php


//delete one
//return 1 or 0

$delete = new Query();
$delete->find("user")->where(['status' => 1])->delete();

// or you can do like this following

$users = new Query();
$users->find('user')->where(['username' => 'laser])->one();

$users->delete();


//delete all
//return 1 or 0

$delete = new Query();
$delete->find("user")->where(['status' => 1])->deleteAll();

// or you can do like this following

$users = new Query();
$users->find('user')->where(['username' => 'laser])->all();

$users->deleteAll();


```



### looping


Looping example,

```php
// looping
if ($result)
    foreach ($result as $res) {
        echo ' id :' . $res->id . ' username is :' . $res->username;
    }

```