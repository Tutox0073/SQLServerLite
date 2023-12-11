---
title: SQLServer Methods
editLink: true
outline: deep
---

# Methods

## SELECT

### GET

You can quickly execute a SELECT query in the database by sending it directly and return a collection.

```php
use Rmunate\SqlServerLite\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query
$query->get();
```

### FIRST

You can also use the 'first' method to retrieve the initial object in the collection.

```php
use Rmunate\Utilities\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query
$query->get()->first();
```

### orderBy

You can also use the 'orderBy' method to fetch a collection ordered in ascending (asc) order.

```php
use Rmunate\Utilities\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query

$query->orderBy('asc')->get();

```

### COUNT

The 'count' method can be used to retrieve the total count of the collection.

```php
use Rmunate\Utilities\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query

$query->get()->count();

```

### PLUCK

The 'pluck' method returns an array with the specified column and an optional index.

```php
use Rmunate\Utilities\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query

$query->pluck('name', 'index');
//Return an array with the column and an optional index.
```

### CHUNK

The chunk method proves useful when dealing with thousands of database records. It retrieves results in small chunks, processing each one within a closure.

```php
use Rmunate\Utilities\SQLServer;

$query = SQLServer::connection('sqlsrv')->select('SELECT * FROM cities');
//prepare the query

$query->chunk(100, function (Collection $users) {
    foreach ($users as $user) {
        // ...
    }
});
```

### ALL

$query->all();
//Return the collection as with the 'get' method.
$query->value('name');
//Return the value of a specific column..
$query->get()->last();
//Return the last object.

$query->value('name')->each(function (object $user) {
    // ...
});
// Works similarly to the `chunk` method but returns a LazyCollection.

$query->max('name');
//Return the value of a specific column..

