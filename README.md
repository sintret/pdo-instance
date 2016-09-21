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

This class have multiple method like "find, where, limit, orderBy, statement". Please see the example using this class.
Please ensure you have include 'Query.php'

### select one

This following code will get single row.
```php
$query = new Query;
$query->find("my_table")
        ->where(['roleId' => 3])
        ->orderBy("id desc");

//print result single row using one()
$one = $query->one();
echo "<pre>";print_r($one);

```