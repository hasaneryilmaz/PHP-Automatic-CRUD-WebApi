# PHP-Automatic-CRUD
PHP Automatic Crud Operations Web API 

## What is PHP-Automatic-CRUD ?
PHP-Automatic-CRUD is a PHP library that provides automatic CRUD operations for your database.

## Installation
- Realede version : [Download](https://github.com/hasaneryilmaz/PHP-Automatic-CRUD/releases "Download")
- Put the api folder in your system directory.
- or
- Composer install : `composer create-project hascoding/webapi myapp`

- and
- Make your database settings in api/DbConnect.php file.
- Import the hascoding_api_auth.sql file in the api folder into your database.
- Create a new token from the hascoding_api_auth table in your database.
- Everything is ready.

## How to use ?

> https://{sitename}/api/{table_name}/{table_column_name}/{id}?auth_key={auth_token}&page={page}

- table_name = 'users';
- table_column_name = 'Id';
- id = 1;
- auth_key = '3bb5e585b3b20a089ba46b7d55c74b50';
- page = 1;

> https://{sitename}/api/users/Id/1?auth_key=3bb5e585b3b20a089ba46b7d55c74b50&page=1

## Methods for {table_name}
- GET: Get all records from {table_name}
> https://{sitename}/api/{table_name}?auth_key={auth_token}&page={page}
- GET: Get a record from {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}?auth_key={auth_token}&page={page}
- POST: Create a new record in {table_name}
> https://{sitename}/api/{table_name}?auth_key={auth_token}
- PUT: Update a record in {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}?auth_key={auth_token}
- DELETE: Delete a record in {table_name} by id
> https://{sitename}/api/{table_name}/{table_column_name}/{id}?auth_key={auth_token}
- DELETE: Delete all records in {table_name}
> https://{sitename}/api/{table_name}/delete?auth_key={auth_token}
