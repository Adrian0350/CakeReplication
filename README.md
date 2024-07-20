# CakePHP Replication for MySQL
CakeReplication helps you setup CakePHP 2.10 (latest and last) Replication for MySQL 5.7 in a more integrated way  
adding a layer between Model and the MySQL default driver.

## Requirements
* PHP `7.1.4`
* CakePHP `2.10.4`
* MySQL `5.7`
* Updating your `app/Config/database.php`

*There are other versions of this implementation but are quite outdated, made for older CakePHP versions that*  
*have missmatching class methods and arguments.*

## Installation
Install the Plugin with [Composer](https://getcomposer.org) from your CakePHP's ROOT directory.

```
 $ composer require --dev adrian0350/cakephp-replication 
```
\
Add CakePHP's datasource Database `core` `path` to your `app/Config/core.php`.

````
 App::build(['Model/Datasource/Database' => App::core('Datasource/Database')]);
````
