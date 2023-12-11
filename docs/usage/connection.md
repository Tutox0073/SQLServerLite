---
title: Numbers To Money
editLink: true
outline: deep
---

# Connections

### Connection

You can establish a connection to the SQL Server database using the credentials set in the config/database.php file.

```php
'sqlsrv' => [
    'driver' => 'sqlsrv',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
]
```

### Extended Connection

The above configuration includes the mandatory data for the connection. If additional parameters, such as a port, are required, you can extend the configuration as follows:

```php
'sqlsrv' => [
    'driver' => 'sqlsrv',
    'url' => env('DATABASE_URL'),
    'instance' => env('DB_INSTANCE'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '1433'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
]
```

These configurations provide the necessary information for both basic and extended SQL Server connections.

After configuring the settings, you can establish the connection using the connection method

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('sqlsrv');

```

### Status

The library provides a status method to check the connection status. You can define a custom timeout or use the default timeout (3).

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::status('sqlsrv')->isConnected();
// true if connected successfully

```

The isConnected method is recommended, as it returns true if the connection is successful.


This allows you to easily verify the status of the SQL Server connection and obtain relevant information.

