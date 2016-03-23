
## Setup LAMP Stack

Tasksel is a LAMP stack utility to install PHP, MySQL, and Apache. We won't need it for automated deployment.
```
$ sudo apt-get install tasksel
$ sudo  tasksel # Select MySQL, PHP, and Apache for installation.
```

Don't forget to restart the server.
```
$ sudo /etc/init.d/apache2 restart
```

PhpMySQL is easy for database management. It's optional for development. Definitely not installed on the production.
Go to  http://localhost/phpmyadmin to test PHPMyAdmin.
```
$ sudo  apt-get  install  phpmyadmin
$ sudo ln -s /usr/share/phpmyadmin /var/www
```

## Create A Database & A User
Log into the MySQL.
```
# Use the password that you used while installing MySQL (During TaskSel)
$ mysql -u root -p
```

Setup a database and a user.
```
# Create a database
CREATE DATABASE <database-name>;

# Create a new user
CREATE USER '<db-username>'@'localhost'
IDENTIFIED BY '<db-user-password>';

# Grant privileges to the user
GRANT ALL PRIVILEGES ON *.* TO '<db-username>'@'localhost'
WITH GRANT OPTION;
```

## Backend Code

### Install Composer
PHP Composer is used to manage PHP packages. It's similar to npm for node.
```
# Install Composer
$ curl -s http://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer.phar

```

And if you're even more lazy, like me, you can create an alias:
```
$ alias composer='/usr/local/bin/composer.phar'
```

## Get the code
Clone the backend repository from Bitbucket.
```
git clone git@bitbucket.org:chinmaypatel/apigarage-backend.git
```

### Get Composer Packages
Go inside the directory and install php packages.

```
# Go inside the repository folder
$ cd apigarage-backend

# Install packages
$ composer install
OR
$ /usr/local/bin/composer.phar install
```

### Setup Settings File
Add the local config file named .env.local.php
```
<?php

return array(
  /* Database */
  'db_type' => '_______',
  'db_host' => '_______',
  'db_name' => '_______',
  'db_username' => '_______',
  'db_password' => '_______',

  /* mailgun */
  'mailgun_domain' => 'andbox196ff6aea54948fc9be5564c7d67c22d.mailgun.org',
  'mailgun_secret' => 'key-b2ff544ae437400a5343d1e83c73d41a',
);
```

### Run Migrations & Run Seed
```
$ php artisan migrate # Installs all the tables
$ php artisan db:seed # Pre-populate some data for the application
```

## Verify Application

```
php artisan serve
```
