# CakePHP Replication for MySQL
CakeReplication helps you setup CakePHP 2.10 (latest and last) Replication for MySQL 5.7 in a more integrated way  
adding a layer between Model and the MySQL default driver.  
*It's been only tested using CakePHP version `^2.10.0`*

## Requirements
* PHP `^7.4`
* CakePHP `^2.10.0`
* MySQL `5.7`
* Updating your `app/Config/database.php`

*There are other versions of this implementation but are quite outdated, made for older CakePHP versions that*  
*have missmatching class methods and arguments.*

## Installation
Install the Plugin with [Composer](https://getcomposer.org) from your CakePHP's ROOT directory.

```
 $ composer require adrian0350/cakephp-replication 
```
\
Load `CakeReplication` Plugin in your `bootstrap.php`.

````
 CakePlugin::load('CakeReplication');
````
\
Add Datasourace in your `database.php` database configuration.
```
class DATABASE_CONFIG {
    public $default = array(
        'datasource'  => 'CakeReplication.Database/MysqlReplication',
        'persistent'  => false,
        'host'        => 'localhost',
        'login'       => 'cakephpuser',
        'password'    => 'c4k3roxx!',
        'database'    => 'my_cakephp_project',
        'prefix'      => ''
    );
}
```
*For a complete example of how to setup replication in your app see example `database.php`*
