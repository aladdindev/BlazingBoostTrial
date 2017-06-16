# Blazing Boost Test
Blazing Boost trial test


## Requirements
* [Zend Framework v1.12.20](https://framework.zend.com/downloads/archives)
* [Phinx](https://github.com/robmorgan/phinx)
* [Composer](https://getcomposer.org/)
* [PHPUnit] (https://phpunit.de)

## Installation

### Project dependencies installation
In order to handle database migration, phinx shall be installed using composer.

```Composer
composer install
```

In addition to that, the Zend Framework v1.12.20 library files shall be copied inside the project's library folder. Please note that allow_url_fopen should be enabled in php.ini.

### Database
A MySQL database needs to be created in order to handle the currency data.

```mysql
CREATE DATABASE currency;
```

After that, two files from the project needs to be updated with the proper database access and credentials info.
The first file is the Zend project application.ini file.

```application/configs/application.ini
; database
...
resources.db.params.host = "localhost"
resources.db.params.username = "root"
resources.db.params.password = ""
resources.db.params.dbname = "currency"
...
```

The second file is phinx configuration file. The project currently uses the development environment of phinx, hence the modification should affect the development config parameters.

```phinx.yml
development:
  ...
  host: localhost
  name: currency
  user: root
  pass: ''
  ...
```

### Database Migration
The database schema shall be imported by phinx using the following command.

```phinx
php vendor/bin/phinx migrate
```

By this far, the project installation is finished and after creating a virtual host, the website shall be working fine.

## Testing
In order to run the PHPUnit unit tests, the phpunit command should run from the ./tests working directory.