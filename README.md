# PHP-Automatic-CRUD
PHP Automatic Crud Operations

## What is PHP-Automatic-CRUD ?
PHP-Automatic-CRUD is a PHP library that provides automatic CRUD operations for your database.

## Installation
- Realede version : [Download](https://github.com/hasaneryilmaz/PHP-Automatic-CRUD/releases "Download")
- Composer install : `composer create-project hascoding/webapi myapp`

- Put the api folder in your system directory.
- Make your database settings in api/DbConnect.php file.
- Everything is ready.

## How to use ?

> https://{sitename}/api/{table_name}/{table_column_name}/{id}

- table_name = 'users';
- table_column_name = 'Id';
- id = 1;

> https://{sitename}/api/users/Id/1

## Methods for {table_name}
- GET: Get all records from {table_name}
> https://{sitename}/api/{table_name}
- GET: Get a record from {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}
- POST: Create a new record in {table_name}
> https://{sitename}/api/{table_name}
- PUT: Update a record in {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}
- DELETE: Delete a record in {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}
- DELETE: Delete all records in {table_name}
> https://{sitename}/api/{table_name}/delete
